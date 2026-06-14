<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Organizadora;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function usuarios()
    {
        $clientes = Cliente::orderBy('nomeCliente')->get();
        $organizadoras = Organizadora::orderBy('nomeOrg')->get();
        
        return view('admin.usuarios', compact('clientes', 'organizadoras'));
    }
}