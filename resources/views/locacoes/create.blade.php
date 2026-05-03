@extends('layouts.app')

@section('title', 'Nova locacao - Locadora Rota Livre')

@section('content')
    <div class="page-header">
        <div>
            <h1>Nova locacao</h1>
            <p class="muted">Escolha cliente, veiculo e periodo de uso.</p>
        </div>
    </div>

    <section class="form-panel">
        <form method="POST" action="{{ route('locacoes.store') }}">
            @csrf
            @include('locacoes._form')
            <div class="actions">
                <button class="btn primary" type="submit">Salvar locacao</button>
                <a class="btn secondary" href="{{ route('locacoes.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
