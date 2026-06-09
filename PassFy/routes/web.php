<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrganizadoraController;   
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventoController;
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

Route::middleware(['auth:cliente,organizadora'])->group(function () {
    Route::post('/create/evento', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{id}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
    Route::post('/eventos/{id}/ativar', [EventoController::class, 'ativar'])->name('eventos.ativar');
    Route::post('/eventos/{id}/cancelar', [EventoController::class, 'cancelar'])->name('eventos.cancelar');
    Route::get('/meus-eventos', [EventoController::class, 'meusEventos'])->name('meus.eventos');

    Route::get('/create/evento', function () {
        return view('eventos.create');
    })->name('eventos.create');

    Route::get('/meus/eventos', function () {
        return redirect()->route('meus.eventos');
    });
});

Route::middleware(['auth:cliente'])->group(function () {
    Route::get('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
});

Route::get('/evento/{id}', [EventoController::class, 'show'])->name('evento.show');
Route::get('/buscar-eventos', [EventoController::class, 'buscar'])->name('eventos.buscar');

Route::delete('/lotes/{id}', [EventoController::class, 'destroyLote'])->name('lotes.destroy');

// Rota para buscar CEP
Route::get('/api/cidade/buscar-por-cep', [CidadeController::class, 'buscarPorCep'])->name('cidade.buscar-por-cep');

//Rotas para buscar no banco de dados
Route::get('/cidades/{uf}', [CidadeController::class, 'getCidadesByUf'])->name('cidades.by.uf');

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