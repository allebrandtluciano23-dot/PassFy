<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Cidade;
use App\Models\Lote;

class EventoController extends Controller
{
    public function meusEventos()
    {
        $eventos = collect(); // coleção vazia como fallback
        
        // Verificar se é organizadora logada
        if (auth('organizadora')->check()) {
            $organizadoraId = auth('organizadora')->user()->idOrg;
            $eventos = Evento::where('idOrg', $organizadoraId)->get();
        }
        // Verificar se é cliente logado
        elseif (auth('cliente')->check()) {
            $clienteId = auth('cliente')->user()->idCliente;
            $eventos = Evento::where('idCliente', $clienteId)->get();
        }
        // Se não estiver logado, redireciona para login
        else {
            return redirect()->route('home')->with('error', 'Você precisa estar logado para acessar seus eventos.');
        }
        
        return view('eventos.meuseventos', compact('eventos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomeEvento' => 'required|string|max:255',
            'dataEvento' => 'required|date',
            'horaEvento' => 'required|date_format:H:i',
            'tipoEvento' => 'required|string|max:100',
            'localEvento' => 'required|string|max:255',
            'descricaoEvento' => 'required|string',
            'idCidade' => 'required|integer|exists:cidade,idCidade',
            'imagemEvento' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        
        // Verificar se o usuário tem permissão (opcional)
        // if ($evento->organizadora_id != auth('organizadora')->id()) {
        //     return redirect()->route('eventos.meus')->with('error', 'Você não pode editar este evento.');
        // }
        
        $cidades = Cidade::orderBy('nomeCidade')->get();
        
        return view('eventos.edit', compact('evento', 'cidades'));
    }

    // Atualizar evento
    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        
        // Validação
        $validated = $request->validate([
            'nomeEvento' => 'required|string|max:255',
            'dataEvento' => 'required|date',
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
                // Deletar imagem antiga
                if ($evento->imagemEvento && Storage::disk('public')->exists($evento->imagemEvento)) {
                    Storage::disk('public')->delete($evento->imagemEvento);
                }
                
                // Salvar nova imagem
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
                        $lote->nomeLote = $loteData['nomeLote'];
                        $lote->quantidadeTotal = $loteData['quantidadeTotal'];
                        $lote->valorIngresso = $loteData['valorIngresso'];
                        $lote->save();
                    } 
                    // Se é um novo lote (ID começa com 'novo_')
                    elseif (str_starts_with($loteId, 'novo_')) {
                        Lote::create([
                            'evento_id' => $evento->idEvento,
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
            
            return back()->withErrors(['error' => 'Erro ao atualizar evento: ' . $e->getMessage()])->withInput();
        }
    }

    // Método para deletar lote (opcional)
    public function destroyLote($id)
    {
        $lote = Lote::findOrFail($id);
        $lote->delete();
        
        return response()->json(['success' => true]);
    }
}
