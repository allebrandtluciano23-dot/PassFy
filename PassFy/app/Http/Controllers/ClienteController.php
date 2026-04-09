<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function login(Request $request)
{
    $dados = [
        'emailCliente' => $request->email,
        'password' => $request->password
    ];

    dd([
        'dados_enviados' => $dados,
        'cliente_encontrado' => Cliente::where('emailCliente', $request->email)->first(),
        'auth_attempt' => Auth::guard('cliente')->attempt($dados)
    ]);
}
}