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
                'city' => 'required|string|max:255',
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
                return response()->json([
                    'success' => false,
                    'message' => 'Erro na validação',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            // Encontrar cidade pelo nome e UF
            $cidade = Cidade::where('nomeCidade', $validated['city'])
                ->where('ufCidade', $validated['state'])
                ->first();

            if (!$cidade) {
                $cidade = Cidade::create([
                    'nomeCidade' => $validated['city'],
                    'ufCidade' => $validated['state'],
                    'cepCidade' => $validated['cep'],
                ]);
            }

            // Criar cliente
            $cliente = Cliente::create([
                'idCidade' => $cidade->idCidade,
                'nomeCliente' => $validated['name'],
                'emailCliente' => $validated['email'],
                'senhaCliente' => $validated['password'],
                'cpfCliente' => $validated['cpf'],
                'cepCliente' => $validated['cep'],
                'enderecoCliente' => $validated['address'],
                'telefoneCliente' => $validated['phone'],
            ]);

            // Criar carrinho para o cliente
            Carrinho::create([
                'idCliente' => $cliente->idCliente,
            ]);

            // Criar carteira digital com saldo inicial de R$ 0,00
            CarteiraDigital::create([
                'idCliente' => $cliente->idCliente,
                'saldo' => 0.00,
            ]);

            DB::commit();

            // Fazer login automático
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
                    'message' => 'Erro ao registrar: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Erro ao registrar: ' . $e->getMessage()])->withInput();
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