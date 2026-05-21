<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'idCidade' => 'required|exists:cidade,idCidade',
            'imagemEvento' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $evento = new Evento();
        $evento->nomeEvento = $validated['nomeEvento'];
        $evento->dataEvento = $validated['dataEvento'];
        $evento->horaEvento = $validated['horaEvento'];
        $evento->tipoEvento = $validated['tipoEvento'];
        $evento->localEvento = $validated['localEvento'];
        $evento->descricaoEvento = $validated['descricaoEvento'];
        $evento->idCidade = $validated['idCidade'];
        $evento->statusEvento = 'A';

        if (Auth::guard('organizadora')->check()) {
            $evento->idOrg = Auth::guard('organizadora')->id();
        }

        if (Auth::guard('cliente')->check()) {
            $evento->idCliente = Auth::guard('cliente')->id();
        }

        if (!$evento->idOrg && !$evento->idCliente) {
            return redirect()->back()->with('error', 'Você precisa estar logado para criar um evento.');
        }

        if ($request->hasFile('imagemEvento')) {
            $nomeImagem = time() . '.' . $request->file('imagemEvento')->extension();
            $imagem = $request->file('imagemEvento')->storeAs('images/eventos', $nomeImagem, 'public');
            $evento->imagemEvento = $imagem;
        }

        $evento->save();

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
