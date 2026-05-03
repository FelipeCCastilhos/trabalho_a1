@extends('layouts.app')

@section('title', 'Editar cliente - Locadora Rota Livre')

@section('content')
    <div class="page-header">
        <div>
            <h1>Editar cliente</h1>
            <p class="muted">{{ $cliente->nome }}</p>
        </div>
    </div>

    <section class="form-panel">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf
            @method('PUT')
            @include('clientes._form', ['cliente' => $cliente])
            <div class="actions">
                <button class="btn primary" type="submit">Atualizar cliente</button>
                <a class="btn secondary" href="{{ route('clientes.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
