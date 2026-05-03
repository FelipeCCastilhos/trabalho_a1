@extends('layouts.app')

@section('title', 'Locacao - Locadora Rota Livre')

@section('content')
    <div class="page-header">
        <div>
            <h1>Locacao #{{ $locacao->id }}</h1>
            <p class="muted">{{ $locacao->cliente->nome }} - {{ $locacao->veiculo->placa }}</p>
        </div>
        <div class="actions">
            <a class="btn secondary" href="{{ route('locacoes.edit', $locacao) }}">Editar</a>
            <a class="btn secondary" href="{{ route('locacoes.index') }}">Voltar</a>
        </div>
    </div>

    <section class="panel">
        <div class="detail-list">
            <div class="detail-item"><strong>Cliente</strong>{{ $locacao->cliente->nome }}</div>
            <div class="detail-item"><strong>Veiculo</strong>{{ $locacao->veiculo->placa }} - {{ $locacao->veiculo->marca }} {{ $locacao->veiculo->modelo }}</div>
            <div class="detail-item"><strong>Retirada</strong>{{ $locacao->data_retirada->format('d/m/Y') }}</div>
            <div class="detail-item"><strong>Devolucao prevista</strong>{{ $locacao->data_devolucao_prevista->format('d/m/Y') }}</div>
            <div class="detail-item"><strong>Devolucao real</strong>{{ $locacao->data_devolucao_real?->format('d/m/Y') ?: 'Pendente' }}</div>
            <div class="detail-item"><strong>Status</strong>{{ $locacao->status_label }}</div>
            <div class="detail-item"><strong>Diarias</strong>{{ $locacao->dias_previstos }}</div>
            <div class="detail-item"><strong>Total previsto</strong>R$ {{ number_format((float) $locacao->valor_total_previsto, 2, ',', '.') }}</div>
        </div>
        @if ($locacao->observacoes)
            <div class="detail-item" style="margin-top: 14px;">
                <strong>Observacoes</strong>{{ $locacao->observacoes }}
            </div>
        @endif
    </section>
@endsection
