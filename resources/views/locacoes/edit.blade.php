@extends('layouts.app')

@section('title', 'Editar locacao - Locadora Rota Livre')

@section('content')
    <div class="page-header">
        <div>
            <h1>Editar locacao</h1>
            <p class="muted">{{ $locacao->cliente->nome ?? 'Cliente' }} - {{ $locacao->veiculo->placa ?? 'Veiculo' }}</p>
        </div>
    </div>

    <section class="form-panel">
        <form method="POST" action="{{ route('locacoes.update', $locacao) }}">
            @csrf
            @method('PUT')
            @include('locacoes._form', ['locacao' => $locacao])
            <div class="actions">
                <button class="btn primary" type="submit">Atualizar locacao</button>
                <a class="btn secondary" href="{{ route('locacoes.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
