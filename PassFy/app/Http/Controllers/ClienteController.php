<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cidade;
use App\Models\Carrinho;
use App\Models\CarteiraDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function register(Request $request)
    {
        // Validar dados
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:cliente,emailCliente',
                'city' => 'required|integer|exists:cidade,idCidade',
                'state' => 'required|string|size:2',
                'cep' => 'required|string|size:8|regex:/^\d{8}$/',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'cpf' => 'required|string|unique:cliente,cpfCliente',
                'password' => 'required|string|min:6',
                'passwordConfirmation' => 'required|string|same:password',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                // Formatar mensagem de erro mais amigável
                $errors = $e->errors();
                $firstError = collect($errors)->flatten()->first();
                return response()->json([
                    'success' => false,
                    'message' => $firstError,
                    'errors' => $errors
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            $cliente = Cliente::create([
                'idCidade' => $validated['city'],
                'nomeCliente' => $validated['name'],
                'emailCliente' => $validated['email'],
                'senhaCliente' => bcrypt($validated['password']),
                'cpfCliente' => $validated['cpf'],
                'cepCliente' => $validated['cep'],
                'enderecoCliente' => $validated['address'],
                'telefoneCliente' => $validated['phone'],
            ]);

            Carrinho::create(['idCliente' => $cliente->idCliente]);
            CarteiraDigital::create([
                'idCliente' => $cliente->idCliente,
                'saldo' => 0.00,
            ]);

            DB::commit();
            Auth::guard('cliente')->login($cliente);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cadastro realizado com sucesso!',
                    'redirect' => route('home')
                ]);
            }

            return redirect()->route('home')->with('success', 'Cadastro realizado com sucesso!');
            
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao cadastrar: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Erro ao cadastrar: ' . $e->getMessage()])->withInput();
        }
    }

    public function login(Request $request)
    {
        $dados = [
            'emailCliente' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('cliente')->attempt($dados)) {
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
}