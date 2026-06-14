<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrganizadoraController;   
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\CarteiraDigitalController;
use App\Http\Controllers\AdminController;
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

    // Criar evento
    Route::get('/create/evento', function () {
        return view('eventos.create');
    })->name('eventos.create');
    
    Route::post('/create/evento', [EventoController::class, 'store'])->name('eventos.store');

    // Editar evento
    Route::get('/eventos/{id}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');

    // Gerenciar status
    Route::post('/eventos/{id}/ativar', [EventoController::class, 'ativar'])->name('eventos.ativar');
    Route::post('/eventos/{id}/cancelar', [EventoController::class, 'cancelar'])->name('eventos.cancelar');

    // Gerenciar lotes
    Route::delete('/lotes/{id}', [EventoController::class, 'destroyLote'])->name('lotes.destroy');

    // Visualizar meus eventos
    Route::get('/meus-eventos', [EventoController::class, 'meusEventos'])->name('meus.eventos');
});

Route::middleware(['auth:cliente'])->group(function () {
    
    // Perfil e ingressos
    Route::get('/meus-ingressos', [ClienteController::class, 'meusIngressos'])->name('meus.ingressos');
    Route::post('/ingressos/{id}/cancelar', [ClienteController::class, 'cancelarIngresso'])->name('ingressos.cancelar');

    // Carrinho
    Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
    Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
    Route::delete('/carrinho/remover/{id}', [CarrinhoController::class, 'remover'])->name('carrinho.remover');
    Route::put('/carrinho/atualizar/{id}', [CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');
    Route::post('/carrinho/finalizar', [CarrinhoController::class, 'finalizar'])->name('carrinho.finalizar');
    
    // Checkout e pagamento
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/pagar', [CheckoutController::class, 'pagar'])->name('checkout.pagar');
    Route::post('/pagamento/simular', [PagamentoController::class, 'simular'])->name('pagamento.simular');
    Route::get('/pagamento/{id}/finalizar', [PagamentoController::class, 'finalizar'])->name('pagamento.finalizar');
    Route::get('/pagamento/finalizar/todos', [PagamentoController::class, 'finalizarTodos'])->name('pagamento.finalizar.todos');

    // Carteira digital
    Route::get('/carteira', [CarteiraDigitalController::class, 'index'])->name('carteira.index');
    Route::post('/carteira/depositar', [CarteiraDigitalController::class, 'depositar'])->name('carteira.depositar');
    Route::get('/cliente/carteira', [CarteiraDigitalController::class, 'index'])->name('cliente.carteira');
});

Route::middleware(['auth:usuario'])->prefix('admin')->group(function () {
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
});

// Listar eventos
Route::get('/evento/{id}', [EventoController::class, 'show'])->name('evento.show');
Route::get('/buscar-eventos', [EventoController::class, 'buscar'])->name('eventos.buscar');

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

// Rota de login padrão
Route::get('/login', function () {
    if (Auth::guard('cliente')->check() || Auth::guard('organizadora')->check() || Auth::guard('usuario')->check()) {
        return redirect()->route('home');
    }
    
    return redirect('/')->with('open_modal', true);
})->name('login');