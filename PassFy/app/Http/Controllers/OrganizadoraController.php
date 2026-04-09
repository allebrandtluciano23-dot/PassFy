<?php

namespace App\Http\Controllers;

use App\Models\Organizadora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizadoraController extends Controller
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
        //
    }
    
    public function login(Request $request)
    {
    if (Auth::guard('organizadora')->attempt([
        'emailOrg' => $request->email,
        'password' => $request->password
    ])) {
        return 'Organizadora logada';
    }

    return 'Erro login organizadora';
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
