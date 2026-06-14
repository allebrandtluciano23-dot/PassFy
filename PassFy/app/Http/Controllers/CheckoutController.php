<?php

namespace App\Http\Controllers;

use App\Models\CarteiraDigital;
use App\Models\Ingresso;
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
            $carteiraSaldo = $cliente->carteiraDigital?->saldo ?? 0;
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
            return redirect()->route('carrinho.index')->with('error', 'Nenhum ingresso selecionado para pagamento.');
        }

        $ingressos = Ingresso::whereIn('idIngresso', $ids)
            ->where('status', 'R')
            ->get();

        if ($ingressos->count() !== count($ids)) {
            return redirect()->route('carrinho.index')->with('error', 'Ingressos inválidos ou não reservados.');
        }

        $total = $ingressos->sum(function ($item) {
            return $item->lote->valorIngresso;
        });

        if ($request->input('forma_pagamento') === 'carteira') {
            $cliente = Auth::guard('cliente')->user();
            $carteira = CarteiraDigital::firstOrCreate([
                'idCliente' => $cliente->idCliente,
            ], ['saldo' => 0.00]);

            if ($carteira->saldo < $total) {
                return redirect()->back()->with('error', 'Saldo insuficiente na carteira digital.');
            }

            DB::transaction(function () use ($ingressos, $carteira, $total) {
                $carteira->saldo -= $total;
                $carteira->save();

                foreach ($ingressos as $ingresso) {
                    $ingresso->status = 'A';
                    $ingresso->save();
                }
            });
        } else {
            foreach ($ingressos as $ingresso) {
                $ingresso->status = 'A';
                $ingresso->save();
            }
        }

        return redirect()->route('meus.ingressos')->with('success', 'Pagamento simulado com sucesso!');
    }
}
