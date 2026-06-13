<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\IngressoCarrinho;
use App\Models\Lote;
use App\Models\Ingresso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarrinhoController extends Controller
{
    // Adicionar item ao carrinho
    public function adicionar(Request $request)
    {
        $request->validate([
            'lote_id' => 'required|exists:lote,idLote',
            'quantidade' => 'required|integer|min:1'
        ]);
        
        $clienteId = auth('cliente')->id();
        $lote = Lote::with('ingressos')->findOrFail($request->lote_id);
        
        // Verificar disponibilidade
        $vendidos = $lote->ingressos()->whereIn('status', ['A', 'R'])->count();
        $disponivel = $lote->quantidadeTotal - $vendidos;
        
        if ($request->quantidade > $disponivel) {
            return response()->json([
                'success' => false,
                'message' => "Apenas {$disponivel} ingressos disponíveis neste lote."
            ], 400);
        }
        
        // Buscar ou criar carrinho do cliente
        $carrinho = Carrinho::firstOrCreate(
            ['idCliente' => $clienteId],
            ['idCliente' => $clienteId]
        );
        
        // Verificar se item já existe no carrinho
        $itemExistente = IngressoCarrinho::where('idCarrinho', $carrinho->idCarrinho)
            ->where('idLote', $request->lote_id)
            ->first();
        
        if ($itemExistente) {
            $novaQuantidade = $itemExistente->quantidade + $request->quantidade;
            
            if ($novaQuantidade > $disponivel) {
                return response()->json([
                    'success' => false,
                    'message' => "Quantidade total excede os {$disponivel} ingressos disponíveis."
                ], 400);
            }
            
            $itemExistente->quantidade = $novaQuantidade;
            $itemExistente->save();
        } else {
            IngressoCarrinho::create([
                'idCarrinho' => $carrinho->idCarrinho,
                'idLote' => $request->lote_id,
                'quantidade' => $request->quantidade,
                'valorUnitario' => $lote->valorIngresso
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Ingresso adicionado ao carrinho!'
        ]);
    }
    
    // Visualizar carrinho
    public function index()
    {
        $clienteId = auth('cliente')->id();
        
        $carrinho = Carrinho::where('idCliente', $clienteId)->first();
        
        if (!$carrinho) {
            return view('/ingressos/carrinho', ['itens' => collect([]), 'total' => 0]);
        }
        
        $itens = IngressoCarrinho::with(['lote.evento', 'lote.evento.cidade'])
            ->where('idCarrinho', $carrinho->idCarrinho)
            ->get();

        $itens = $itens->filter(function ($item) use ($carrinho) {
            $lote = $item->lote;
            if (!$lote) {
                return false;
            }

            $vendidos = $lote->ingressos()->whereIn('status', ['A', 'R'])->count();
            $disponivel = max($lote->quantidadeTotal - $vendidos, 0);

            if ($disponivel <= 0) {
                IngressoCarrinho::where('idCarrinho', $carrinho->idCarrinho)
                    ->where('idLote', $item->idLote)
                    ->delete();
                return false;
            }

            if ($item->quantidade > $disponivel) {
                $item->quantidade = $disponivel;
                $item->save();
            }

            $item->disponivel = $disponivel;
            return true;
        });
        
        $total = $itens->sum(function ($item) {
            return $item->quantidade * $item->valorUnitario;
        });
        
        return view('/ingressos/carrinho', compact('itens', 'total'));
    }
    
    // Remover item do carrinho
    public function remover($id)
    {
        try {
            $clienteId = auth('cliente')->id();
            $carrinho = Carrinho::where('idCliente', $clienteId)->first();
            
            if (!$carrinho) {
                return response()->json(['success' => false, 'message' => 'Carrinho não encontrado.'], 404);
            }
            
            // Verificar se o item existe
            $item = IngressoCarrinho::where('idCarrinho', $carrinho->idCarrinho)
                ->where('idLote', $id)
                ->first();
            
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item não encontrado.'], 404);
            }
            
            // Deletar usando query builder para garantir que apenas este item seja removido
            IngressoCarrinho::where('idCarrinho', $carrinho->idCarrinho)
                ->where('idLote', $id)
                ->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao remover item: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Atualizar quantidade
    public function atualizar(Request $request, $id)
    {
        try {
            $request->validate([
                'quantidade' => 'required|integer|min:0'
            ]);
            
            $clienteId = auth('cliente')->id();
            $carrinho = Carrinho::where('idCliente', $clienteId)->first();
            
            if (!$carrinho) {
                return response()->json(['success' => false, 'message' => 'Carrinho não encontrado.'], 404);
            }
            
            $item = IngressoCarrinho::where('idCarrinho', $carrinho->idCarrinho)
                ->where('idLote', $id)
                ->first();
            
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item não encontrado.'], 404);
            }
            
            if ($request->quantidade <= 0) {
                // Deletar usando query builder
                IngressoCarrinho::where('idCarrinho', $carrinho->idCarrinho)
                    ->where('idLote', $id)
                    ->delete();
            } else {
                // Verificar disponibilidade
                $lote = Lote::findOrFail($id);
                $vendidos = $lote->ingressos()->whereIn('status', ['A', 'R'])->count();
                $disponivel = $lote->quantidadeTotal - $vendidos;
                
                if ($request->quantidade > $disponivel) {
                    return response()->json([
                        'success' => false,
                        'message' => "Apenas {$disponivel} ingressos disponíveis."
                    ], 400);
                }
                
                $item->quantidade = $request->quantidade;
                $item->save();
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao atualizar quantidade: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Finalizar compra (ir para checkout)
    public function finalizar(Request $request)
    {
        $request->validate([
            'quantidades' => 'required|array|min:1',
            'quantidades.*.itemId' => 'required|integer',
            'quantidades.*.quantidade' => 'required|integer|min:1',
        ]);

        $clienteId = auth('cliente')->id();
        $carrinho = Carrinho::where('idCliente', $clienteId)->first();
        
        if (!$carrinho) {
            return response()->json(['success' => false, 'message' => 'Carrinho vazio.'], 422);
        }
        
        $itens = IngressoCarrinho::with('lote')
            ->where('idCarrinho', $carrinho->idCarrinho)
            ->get();
        
        if ($itens->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Carrinho vazio.'], 422);
        }

        $quantidades = collect($request->quantidades)->keyBy('itemId')->map(function ($item) {
            return intval($item['quantidade']);
        });

        $removedItems = [];

        // Verificar disponibilidade novamente antes de finalizar
        foreach ($itens as $item) {
            $itemQuantidade = $quantidades->get($item->idLote, $item->quantidade);
            $lote = $item->lote;
            $vendidos = $lote->ingressos()->whereIn('status', ['A', 'R'])->count();
            $disponivel = $lote->quantidadeTotal - $vendidos;

            if ($disponivel <= 0) {
                IngressoCarrinho::where('idCarrinho', $carrinho->idCarrinho)
                    ->where('idLote', $item->idLote)
                    ->delete();

                $removedItems[] = $lote->nomeLote;
                continue;
            }

            if ($itemQuantidade > $disponivel) {
                return response()->json([
                    'success' => false,
                    'message' => "O lote '{$lote->nomeLote}' tem apenas {$disponivel} ingressos disponíveis no momento."
                ], 422);
            }

            $item->quantidade = $itemQuantidade;
        }

        if (!empty($removedItems)) {
            $nomes = implode(', ', $removedItems);
            return response()->json([
                'success' => false,
                'message' => "Os seguintes itens foram removidos do carrinho porque não há disponibilidade: {$nomes}. Atualize a página e tente novamente."
            ], 422);
        }

        // Calcular total
        $total = $itens->sum(function ($item) {
            return $item->quantidade * $item->valorUnitario;
        });
        
        // Criar ingressos reservados (status 'R') para cada item do carrinho e remover itens do carrinho
        DB::transaction(function () use ($itens, $carrinho) {
            foreach ($itens as $item) {
                for ($i = 0; $i < $item->quantidade; $i++) {
                    // Gerar codigoUnico único
                    do {
                        $codigo = random_int(100000000, 999999999);
                    } while (Ingresso::where('codigoUnico', $codigo)->exists());

                    Ingresso::create([
                        'idLote' => $item->idLote,
                        'codigoUnico' => $codigo,
                        'status' => 'R',
                    ]);
                }
            }

            // Remover todos os itens do carrinho do cliente após reservar ingressos
            IngressoCarrinho::where('idCarrinho', $carrinho->idCarrinho)->delete();
        });

        return view('ingressos.checkout', compact('itens', 'total'));
    }
}
