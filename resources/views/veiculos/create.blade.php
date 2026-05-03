@extends('layouts.app')

@section('title', 'Novo veiculo - Locadora Rota Livre')

@section('content')
    <div class="page-header">
        <div>
            <h1>Novo veiculo</h1>
            <p class="muted">Cadastre um carro da frota.</p>
        </div>
    </div>

    <section class="form-panel">
        <form method="POST" action="{{ route('veiculos.store') }}">
            @csrf
            @include('veiculos._form')
            <div class="actions">
                <button class="btn primary" type="submit">Salvar veiculo</button>
                <a class="btn secondary" href="{{ route('veiculos.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
