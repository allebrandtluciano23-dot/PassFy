<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
#use App\Http\Controllers\OrganizadoraController;   
#use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;

Route::post('/login/cliente', [ClienteController::class, 'login']);
#Route::post('/login/organizadora', [OrganizadoraController::class, 'login']);
#Route::post('/login/usuario', [UsuarioController::class, 'login']);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home');