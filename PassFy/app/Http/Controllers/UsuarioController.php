<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function register(Request $request)
    {
        // Validar dados
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:6',
                'passwordConfirmation' => 'required|string|same:password',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro na validação',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Criar usuário
            $usuario = Usuario::create([
                'nomeUsuario' => $validated['name'],
                'senhaUsuario' => $validated['password'],
            ]);

            // Fazer login automático
            Auth::guard('usuario')->login($usuario);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cadastro realizado com sucesso!',
                    'redirect' => route('home')
                ]);
            }

            return redirect()->route('home')->with('success', 'Cadastro realizado com sucesso!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao registrar: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Erro ao registrar: ' . $e->getMessage()])->withInput();
        }
    }

    public function login(Request $request)
    {
        if (Auth::guard('usuario')->attempt([
            'nomeUsuario' => $request->username,
            'password' => $request->password
        ])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login realizado com sucesso!',
                    'redirect' => route('home')
                ]);
            }
            return redirect()->route('home')->with('success', 'Login realizado com sucesso!');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou senha incorretos.'
            ], 401);
        }

        return back()->withErrors(['email' => 'Email ou senha incorretos.']);
    }

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
    }
}
