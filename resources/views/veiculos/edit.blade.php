@extends('layouts.app')

@section('title', 'Editar veiculo - Locadora Prendatta')

@section('content')
    {{-- Tela de edicao de veiculo: permite alterar dados da frota e status operacional. --}}
    <div class="page-header">
        <div>
            <h1>Editar veiculo</h1>
            <p class="muted">{{ $veiculo->placa }} - {{ $veiculo->marca }} {{ $veiculo->modelo }}</p>
        </div>
    </div>

    <section class="form-panel">
        <form method="POST" action="{{ route('veiculos.update', $veiculo) }}">
            @csrf
            @method('PUT')
            @include('veiculos._form', ['veiculo' => $veiculo])
            <div class="actions">
                <button class="btn primary" type="submit">Atualizar veiculo</button>
                <a class="btn secondary" href="{{ route('veiculos.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
