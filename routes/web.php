<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;

// Rotas publicas de autenticacao manual, sem Breeze/Jetstream.
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    // A pagina inicial autenticada e o painel de resumo exigido pelo trabalho.
    Route::get('/', DashboardController::class)->name('dashboard');

    // Admin e atendente podem acessar clientes e locacoes.
    Route::resource('clientes', ClienteController::class);

    Route::resource('locacoes', LocacaoController::class)->parameters([
        'locacoes' => 'locacao',
    ]);

    // Veiculos e usuarios sao areas administrativas.
    Route::resource('veiculos', VeiculoController::class)->middleware('profile:admin');
    Route::get('usuarios', [UsuarioController::class, 'index'])
        ->middleware('profile:admin')
        ->name('usuarios.index');
});
