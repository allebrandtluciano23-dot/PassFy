<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrganizadoraController;   
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\Auth\LoginController;

// Rotas de login
Route::post('/login/cliente', [ClienteController::class, 'login'])->name('login.cliente');
Route::post('/login/organizadora', [OrganizadoraController::class, 'login'])->name('login.organizadora');
Route::post('/login/usuario', [UsuarioController::class, 'login'])->name('login.usuario');

// Rota de logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rotas de cadastro
Route::post('/register/cliente', [ClienteController::class, 'register'])->name('register.cliente.store');
Route::post('/register/organizadora', [OrganizadoraController::class, 'register'])->name('register.organizadora.store');
Route::post('/register/usuario', [UsuarioController::class, 'register'])->name('register.usuario.store');

// Rota para buscar CEP
Route::get('/api/cidade/search-by-cep', [CidadeController::class, 'searchByCep'])->name('cidade.search-by-cep');

// Rotas para as páginas
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/register/cliente', function () {
    return view('auth.register');
})->name('register.cliente');

Route::get('/register/organizadora', function () {
    return view('auth.register');
})->name('register.organizadora');

Route::get('/register/usuario', function () {
    return view('auth.register');
})->name('register.usuario');