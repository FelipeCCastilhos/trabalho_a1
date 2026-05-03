@extends('layouts.app')

@section('title', 'Novo cliente - Locadora Rota Livre')

@section('content')
    <div class="page-header">
        <div>
            <h1>Novo cliente</h1>
            <p class="muted">Informe dados pessoais e CNH do motorista.</p>
        </div>
    </div>

    <section class="form-panel">
        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf
            @include('clientes._form')
            <div class="actions">
                <button class="btn primary" type="submit">Salvar cliente</button>
                <a class="btn secondary" href="{{ route('clientes.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
