<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;

// A pagina inicial e o painel de resumo exigido pelo trabalho.
Route::get('/', DashboardController::class)->name('dashboard');

// CRUDs principais da locadora.
Route::resource('clientes', ClienteController::class);
Route::resource('veiculos', VeiculoController::class);

// Define o parametro correto em portugues: locacoes/{locacao}.
Route::resource('locacoes', LocacaoController::class)->parameters([
    'locacoes' => 'locacao',
]);
