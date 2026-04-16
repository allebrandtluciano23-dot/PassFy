<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrganizadoraController;   
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;

Route::post('/login/cliente', [ClienteController::class, 'login'])->name('login.cliente');
Route::post('/login/organizadora', [OrganizadoraController::class, 'login'])->name('login.organizadora');
Route::post('/login/usuario', [UsuarioController::class, 'login'])->name('login.usuario');



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