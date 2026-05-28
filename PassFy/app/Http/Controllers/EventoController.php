<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Evento $evento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evento $evento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento)
    {
        //
    }
}
