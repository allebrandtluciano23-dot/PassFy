<?php

namespace App\Http\Controllers;

use App\Models\Ingresso;
use Illuminate\Http\Request;

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

        return view('ingressos.checkout', ['itens' => $grouped, 'total' => $total]);
    }
}
