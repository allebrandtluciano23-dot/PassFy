<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Services\CepService;
use Illuminate\Http\Request;

class CidadeController extends Controller
{
    protected $cepService;

    public function __construct(CepService $cepService)
    {
        $this->cepService = $cepService;
    }

    /**
     * Busca cidade e UF por CEP
     */
    public function searchByCep(Request $request)
    {
        try {
            $cep = $request->input('cep');

            if (!$cep) {
                return response()->json([
                    'success' => false,
                    'message' => 'CEP é obrigatório',
                ], 400);
            }

            $resultado = $this->cepService->buscarPorCep($cep);

            if ($resultado['success']) {
                return response()->json($resultado, 200);
            } else {
                return response()->json($resultado, 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
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
    public function show(Cidade $cidade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cidade $cidade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cidade $cidade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cidade $cidade)
    {
        //
    }
}
