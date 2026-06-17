<?php

namespace App\Http\Controllers;

use App\Models\CarteiraDigital;
use App\Models\Ingresso;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $ingressoParam = $request->query('ingresso');
        $ingressosParam = $request->query('ingressos');

        $ids = [];
        if ($ingressoParam) {
            $ids[] = intval($ingressoParam);
        } elseif ($ingressosParam) {
            $ids = array_filter(array_map('intval', explode(',', $ingressosParam)));
        }

        if (empty($ids)) {
            return redirect()->route('carrinho.index')->with('error', 'Nenhum ingresso selecionado para pagamento.');
        }

        $ingressos = Ingresso::with('lote.evento')
            ->whereIn('idIngresso', $ids)
            ->where('status', 'R')
            ->get();

        if ($ingressos->isEmpty()) {
            return redirect()->route('carrinho.index')->with('error', 'Ingressos inválidos ou não reservados.');
        }

        // Agrupar por lote para montar itens (quantidade por lote)
        $grouped = $ingressos->groupBy('idLote')->map(function ($group) {
            $lote = $group->first()->lote;
            $quantidade = $group->count();
            return (object) [
                'lote' => $lote,
                'quantidade' => $quantidade,
                'valorUnitario' => $lote->valorIngresso,
            ];
        })->values();

        $total = $grouped->sum(function ($item) {
            return $item->quantidade * $item->valorUnitario;
        });

        $carteiraSaldo = 0;
        if ($cliente = Auth::guard('cliente')->user()) {
            $carteira = CarteiraDigital::where('idCliente', $cliente->idCliente)->first();
            $carteiraSaldo = $carteira?->saldo ?? 0;
        }

        return view('ingressos.checkout', [
            'itens' => $grouped,
            'total' => $total,
            'carteiraSaldo' => $carteiraSaldo,
            'ingressoIds' => implode(',', $ids),
        ]);
    }

public function pagar(Request $request)
    {
        $request->validate([
            'forma_pagamento' => 'required|in:cartao,pix,carteira',
            'ingressos' => 'required|string',
        ]);

        $ids = array_filter(array_map('intval', explode(',', $request->input('ingressos'))));
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Nenhum ingresso selecionado.'], 422);
        }

        $ingressos = Ingresso::with('lote')
            ->whereIn('idIngresso', $ids)
            ->where('status', 'R')
            ->get();

        if ($ingressos->count() !== count($ids)) {
            return response()->json(['success' => false, 'message' => 'Ingressos inválidos ou não reservados.'], 422);
        }

        $cliente = Auth::guard('cliente')->user();
        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'Você precisa estar logado para realizar o pagamento.'], 401);
        }

        $total = $ingressos->sum(function ($item) {
            return $item->lote->valorIngresso;
        });

        DB::beginTransaction();

        try {
            if ($request->input('forma_pagamento') === 'carteira') {
                $carteira = CarteiraDigital::firstOrCreate(
                    ['idCliente' => $cliente->idCliente],
                    ['saldo' => 0.00]
                );

                if ($carteira->saldo < $total) {
                    return response()->json(['success' => false, 'message' => 'Saldo insuficiente na carteira digital.'], 422);
                }

                $carteira->saldo -= $total;
                $carteira->save();
            }

            // Criar registro de VENDA
            $venda = Venda::create([
                'idCliente' => $cliente->idCliente,
                'quantidadeVenda' => $ingressos->count(),
                'dataCompra' => now(),
                'formaPagamento' => $request->input('forma_pagamento'),
                'valorTotal' => $total,
            ]);

            foreach ($ingressos as $ingresso) {
                $ingresso->status = 'A';
                $ingresso->save();

                DB::table('ingresso_venda')->insert([
                    'idIngresso' => $ingresso->idIngresso,
                    'idVenda' => $venda->idVenda,
                    'quantidade' => 1,
                    'valorUnitario' => $ingresso->lote->valorIngresso,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('meus.ingressos'),
                'message' => 'Pagamento realizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }
}