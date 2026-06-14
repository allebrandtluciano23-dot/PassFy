<?php

namespace App\Http\Controllers;

use App\Models\CarteiraDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarteiraDigitalController extends Controller
{
    public function index()
    {
        $clienteId = auth('cliente')->id();
        
        $carteira = CarteiraDigital::firstOrCreate(
            ['idCliente' => $clienteId],
            ['saldo' => 0.00]
        );
        
        return view('cliente.carteira', ['saldo' => $carteira->saldo]);
    }
    
    public function depositar(Request $request)
    {
        $request->validate([
            'valor' => 'required|numeric|min:1'
        ]);
        
        $clienteId = auth('cliente')->id();
        $valor = $request->valor;
        
        DB::beginTransaction();
        
        try {
            $carteira = CarteiraDigital::firstOrCreate(
                ['idCliente' => $clienteId],
                ['saldo' => 0.00]
            );
            
            $carteira->saldo += $valor;
            $carteira->save();
            
            DB::commit();
            
            return response()->json(['success' => true, 'saldo' => $carteira->saldo]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}