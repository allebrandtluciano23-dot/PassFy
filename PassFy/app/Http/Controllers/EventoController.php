<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\CarteiraDigital;
use App\Models\Pedido;
use App\Models\Cidade;
use App\Models\Lote;
use App\Models\Ingresso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
    public function meusEventos()
    {
        $eventos = collect(); // coleção vazia como fallback
        
        // Verificar se é organizadora logada
        if (auth('organizadora')->check()) {
            $organizadoraId = auth('organizadora')->user()->idOrg;
            $eventos = Evento::with(['cidade', 'lotes'])
            ->where('idOrg', $organizadoraId)
            ->orderBy('created_at', 'desc')
            ->simplePaginate(12);
        }
        // Verificar se é cliente logado
        elseif (auth('cliente')->check()) {
            $clienteId = auth('cliente')->user()->idCliente;
            $eventos = Evento::with(['cidade', 'lotes'])
            ->where('idCliente', $clienteId)
            ->orderBy('created_at', 'desc')
            ->simplePaginate(12);
        }
        // Se não estiver logado, redireciona para login
        else {
            return redirect()->route('home')->with('error', 'Você precisa estar logado para acessar seus eventos.');
        }

        $statusLabels = [
            'R' => 'Rascunho',
            'A' => 'Ativo',
            'E' => 'Esgotado',
            'C' => 'Cancelado',
            'X' => 'Encerrado',
        ];
        
        return view('eventos.meuseventos', compact('eventos', 'statusLabels'));
    }

    public function buscar(Request $request)
    {
        $query = Evento::with(['cidade', 'lotes'])
            ->where('statusEvento', 'A')
            ->where('dataEvento', '>=', Carbon::today());

        // Filtro por nome do evento
        if ($request->filled('name')) {
            $query->where('nomeEvento', 'LIKE', '%' . $request->name . '%');
        }

        // Filtro por cidade
        if ($request->filled('city')) {
            $query->whereHas('cidade', function ($q) use ($request) {
                $q->where('nomeCidade', 'LIKE', '%' . $request->city . '%');
            });
        }

        // Filtro por data
        if ($request->filled('date')) {
            $query->whereDate('dataEvento', $request->date);
        }

        $eventos = $query->orderBy('dataEvento', 'asc')->simplePaginate(32);

        // Calcular preço mínimo
        foreach ($eventos as $evento) {
            $evento->preco_minimo = $evento->lotes->min('valorIngresso');
        }

        return view('eventos.busca', compact('eventos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomeEvento' => 'required|string|max:255',
            'dataEvento' => 'required|date|after_or_equal:today',
            'horaEvento' => 'required|date_format:H:i',
            'tipoEvento' => 'required|string|max:100',
            'localEvento' => 'required|string|max:255',
            'descricaoEvento' => 'required|string',
            'idCidade' => 'required|integer|exists:cidade,idCidade',
            'imagemEvento' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'lotes' => 'required|array|min:1',
            'lotes.*.nomeLote' => 'required|string|max:255',
            'lotes.*.quantidadeTotal' => 'required|integer|min:1',
            'lotes.*.valorIngresso' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $evento = new Evento();
            $evento->nomeEvento = $validated['nomeEvento'];
            $evento->dataEvento = $validated['dataEvento'];
            $evento->horaEvento = $validated['horaEvento'];
            $evento->tipoEvento = $validated['tipoEvento'];
            $evento->localEvento = $validated['localEvento'];
            $evento->descricaoEvento = $validated['descricaoEvento'];
            $evento->idCidade = $validated['idCidade'];
            $evento->statusEvento = 'R';

            if (Auth::guard('organizadora')->check()) {
                $evento->idOrg = Auth::guard('organizadora')->user()->idOrg;
            }

            if (Auth::guard('cliente')->check()) {
                $evento->idCliente = Auth::guard('cliente')->user()->idCliente;
            }

            if ($request->hasFile('imagemEvento')) {
                $nomeImagem = time() . '.' . $request->file('imagemEvento')->extension();
                $imagem = $request->file('imagemEvento')->storeAs('images/eventos', $nomeImagem, 'public');
                $evento->imagemEvento = $imagem;
            }

            $evento->save();

            foreach ($validated['lotes'] as $loteData) {
                $evento->lotes()->create([
                    'nomeLote' => $loteData['nomeLote'],
                    'quantidadeTotal' => $loteData['quantidadeTotal'],
                    'valorIngresso' => $loteData['valorIngresso'],
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Erro ao criar evento: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('home')->with('success', 'Evento criado com sucesso!');
    }

    public function edit($id)
    {
        $evento = Evento::with(['cidade', 'lotes'])->findOrFail($id);
        
        // Verificar se o usuário tem permissão para editar
        $organizadoraId = auth('organizadora')->id();
        $clienteId = auth('cliente')->id();
        
        $temPermissao = false;
        
        // Verificar se é a organizadora que criou o evento
        if ($organizadoraId && $evento->idOrg == $organizadoraId) {
            $temPermissao = true;
        }
        
        // Verificar se é o cliente que criou o evento
        if ($clienteId && $evento->idCliente == $clienteId) {
            $temPermissao = true;
        }
        
        // Se não tiver permissão, redirecionar
        if (!$temPermissao) {
            return redirect()->route('meus.eventos')->with('error', 'Você não tem permissão para editar este evento.');
        }
        
        $cidades = Cidade::orderBy('nomeCidade')->get();
        
        return view('eventos.edit', compact('evento', 'cidades'));
    }

    // Atualizar evento
    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        
        // Verificar permissão
        $organizadoraId = auth('organizadora')->id();
        $clienteId = auth('cliente')->id();
        
        $temPermissao = ($organizadoraId && $evento->idOrg == $organizadoraId) ||
                        ($clienteId && $evento->idCliente == $clienteId);
        
        if (!$temPermissao) {
            return redirect()->route('meus.eventos')->with('error', 'Você não tem permissão para editar este evento.');
        }
        
        // Validação
        $validated = $request->validate([
            'nomeEvento' => 'required|string|max:255',
            'dataEvento' => 'required|date|after_or_equal:today',
            'horaEvento' => 'required',
            'tipoEvento' => 'required|string',
            'idCidade' => 'required|exists:cidade,idCidade',
            'localEvento' => 'required|string|max:255',
            'descricaoEvento' => 'required|string',
            'imagemEvento' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lotes' => 'nullable|array'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Atualizar dados do evento
            $evento->nomeEvento = $validated['nomeEvento'];
            $evento->dataEvento = $validated['dataEvento'];
            $evento->horaEvento = $validated['horaEvento'];
            $evento->tipoEvento = $validated['tipoEvento'];
            $evento->idCidade = $validated['idCidade'];
            $evento->localEvento = $validated['localEvento'];
            $evento->descricaoEvento = $validated['descricaoEvento'];
            
            // Processar nova imagem
            if ($request->hasFile('imagemEvento')) {
                if ($evento->imagemEvento && Storage::disk('public')->exists($evento->imagemEvento)) {
                    Storage::disk('public')->delete($evento->imagemEvento);
                }
                $path = $request->file('imagemEvento')->store('eventos', 'public');
                $evento->imagemEvento = $path;
            }
            
            $evento->save();
            
            // Atualizar lotes existentes e criar novos
            if ($request->has('lotes')) {
                foreach ($request->lotes as $loteId => $loteData) {
                    // Se o lote tem ID numérico (lote existente)
                    if (is_numeric($loteId) && Lote::where('idLote', $loteId)->exists()) {
                        $lote = Lote::find($loteId);
                        
                        // 👇 VERIFICAÇÃO DE INGRESSOS VENDIDOS/RESERVADOS
                        $ingressosVinculados = $lote->ingressos()
                            ->whereIn('status', ['A', 'R'])
                            ->count();
                        
                        // Verificar se a nova quantidade é menor que ingressos já vendidos
                        if ($loteData['quantidadeTotal'] < $ingressosVinculados) {
                            throw new \Exception("O lote '{$lote->nomeLote}' tem {$ingressosVinculados} ingressos já vendidos/reservados. A quantidade total não pode ser menor que este número.");
                        }
                        
                        $lote->nomeLote = $loteData['nomeLote'];
                        $lote->quantidadeTotal = $loteData['quantidadeTotal'];
                        $lote->valorIngresso = $loteData['valorIngresso'];
                        $lote->save();
                    } 
                    // Se é um novo lote (ID começa com 'novo_')
                    elseif (str_starts_with($loteId, 'novo_')) {
                        Lote::create([
                            'idEvento' => $evento->idEvento,
                            'nomeLote' => $loteData['nomeLote'],
                            'quantidadeTotal' => $loteData['quantidadeTotal'],
                            'valorIngresso' => $loteData['valorIngresso'],
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('meus.eventos')->with('success', 'Evento atualizado com sucesso!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    // Método para deletar lote (opcional)
    public function destroyLote($id)
    {
        $lote = Lote::findOrFail($id);
        
        // Verificar permissão (dono do evento)
        $evento = $lote->evento;
        $organizadoraId = auth('organizadora')->id();
        $clienteId = auth('cliente')->id();
        
        $temPermissao = ($organizadoraId && $evento->idOrg == $organizadoraId) ||
                        ($clienteId && $evento->idCliente == $clienteId);
        
        if (!$temPermissao) {
            return response()->json(['success' => false, 'message' => 'Sem permissão.'], 403);
        }
        
        // Verificar se tem ingressos vendidos/reservados
        $ingressosVinculados = $lote->ingressos()
            ->whereIn('status', ['A', 'R'])
            ->exists();
        
        if ($ingressosVinculados) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível excluir este lote pois ele possui ingressos já vendidos ou reservados.'
            ], 400);
        }
        
        $lote->delete();
        
        return response()->json(['success' => true]);
    }

    public function ativar($id)
    {
        $evento = Evento::findOrFail($id);
        
        // Verificar se a data é futura
        if ($evento->dataEvento < Carbon::now()) {
            return redirect()->route('meus.eventos')->with('error', 'Não é possível ativar um evento com data passada.');
        }
        
        // Verificar se tem pelo menos um lote
        if ($evento->lotes()->count() == 0) {
            return redirect()->route('meus.eventos')->with('error', 'Não é possível ativar: evento sem lotes.');
        }
        
        // Verificar se os lotes estão válidos
        $lotesInvalidos = $evento->lotes()->where(function($q) {
            $q->whereNull('nomeLote')
            ->orWhere('quantidadeTotal', '<=', 0)
            ->orWhere('valorIngresso', '<', 0);
        })->exists();
        
        if ($lotesInvalidos) {
            return redirect()->route('meus.eventos')->with('error', 'Não é possível ativar: lotes com dados inválidos.');
        }
        
        $evento->statusEvento = 'A';
        $evento->save();
        
        return redirect()->route('meus.eventos')->with('success', 'Evento ativado com sucesso!');
    }

    // Cancelar evento
    public function cancelar($id)
    {
        $evento = Evento::findOrFail($id);
        $statusAntigo = $evento->statusEvento;
        
        DB::beginTransaction();
        
        try {
            // Mudar status do evento
            $evento->statusEvento = 'C';
            $evento->save();
            
            // Buscar todos os ingressos ativos/reservados deste evento
            $ingressos = Ingresso::whereHas('lote', function($q) use ($evento) {
                $q->where('idEvento', $evento->idEvento);
            })->whereIn('status', ['A', 'R'])->get();

            // Reembolsar cada ingresso vendido (status 'A')
            $ingressosVendidos = $ingressos->where('status', 'A');
            foreach ($ingressosVendidos as $ingresso) {
                $vendaIngresso = \App\Models\IngressoVenda::where('idIngresso', $ingresso->idIngresso)->first();
                if ($vendaIngresso) {
                    $venda = \App\Models\Venda::find($vendaIngresso->idVenda);
                    if ($venda && $venda->idCliente) {
                        $carteira = CarteiraDigital::firstOrCreate(
                            ['idCliente' => $venda->idCliente],
                            ['saldo' => 0.00]
                        );
                        $carteira->saldo += $vendaIngresso->valorUnitario;
                        $carteira->save();
                    }
                }
            }

            // Cancelar todos os ingressos
            foreach ($ingressos as $ingresso) {
                $ingresso->status = 'C';
                $ingresso->save();
            }
            
            DB::commit();
            
            $mensagem = "Evento cancelado. " . $ingressos->count() . " ingressos foram reembolsados.";
            return redirect()->route('meus.eventos')->with('success', $mensagem);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cancelar evento: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $evento = Evento::with(['cidade', 'lotes.ingressos'])->findOrFail($id);
        
        // Verificar se evento está ativo ou esgotado (para visualização)
        if ($evento->statusEvento != 'A' && $evento->statusEvento != 'E') {
            return redirect()->route('home')->with('error', 'Evento não disponível.');
        }
        
        // Calcular quantidade disponível por lote
        foreach ($evento->lotes as $lote) {
            $vendidos = $lote->ingressos->where('status', 'A')->count();
            $reservados = $lote->ingressos->where('status', 'R')->count();
            $lote->disponivel = $lote->quantidadeTotal - $vendidos - $reservados;
        }
        
        return view('eventos.show', compact('evento'));
    }
}

