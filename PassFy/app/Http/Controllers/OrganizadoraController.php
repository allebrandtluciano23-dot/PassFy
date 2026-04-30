<?php

namespace App\Http\Controllers;

use App\Models\Organizadora;
use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizadoraController extends Controller
{
    public function register(Request $request)
    {
        // Validar dados
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:organizadora,emailOrg',
                'city' => 'required|string|max:255',
                'state' => 'required|string|size:2',
                'cep' => 'required|string|size:8|regex:/^\d{8}$/',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'cnpj' => 'required|string|unique:organizadora,cnpjOrg',
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

            // Criar organizadora
            $organizadora = Organizadora::create([
                'idCidade' => $cidade->idCidade,
                'nomeOrg' => $validated['name'],
                'emailOrg' => $validated['email'],
                'senhaOrg' => $validated['password'],
                'cnpjOrg' => $validated['cnpj'],
                'cepOrg' => $validated['cep'],
                'enderecoOrg' => $validated['address'],
                'telefoneOrg' => $validated['phone'],
            ]);

            // Fazer login automático
            Auth::guard('organizadora')->login($organizadora);

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
        if (Auth::guard('organizadora')->attempt([
            'emailOrg' => $request->email,
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
    public function show(Organizadora $organizadora)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organizadora $organizadora)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organizadora $organizadora)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organizadora $organizadora)
    {
        //
    }
}
