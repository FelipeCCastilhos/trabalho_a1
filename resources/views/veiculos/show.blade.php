@extends('layouts.app')

@section('title', 'Veiculo - Locadora Rota Livre')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $veiculo->marca }} {{ $veiculo->modelo }}</h1>
            <p class="muted">{{ $veiculo->placa }}</p>
        </div>
        <div class="actions">
            <a class="btn secondary" href="{{ route('veiculos.edit', $veiculo) }}">Editar</a>
            <a class="btn secondary" href="{{ route('veiculos.index') }}">Voltar</a>
        </div>
    </div>

    <section class="panel">
        <div class="detail-list">
            <div class="detail-item"><strong>Marca</strong>{{ $veiculo->marca }}</div>
            <div class="detail-item"><strong>Modelo</strong>{{ $veiculo->modelo }}</div>
            <div class="detail-item"><strong>Ano</strong>{{ $veiculo->ano }}</div>
            <div class="detail-item"><strong>Categoria</strong>{{ \App\Models\Veiculo::CATEGORIAS[$veiculo->categoria] ?? $veiculo->categoria }}</div>
            <div class="detail-item"><strong>Diaria</strong>R$ {{ number_format((float) $veiculo->valor_diaria, 2, ',', '.') }}</div>
            <div class="detail-item"><strong>Status</strong>{{ \App\Models\Veiculo::STATUS_LABELS[$veiculo->status] ?? $veiculo->status }}</div>
        </div>
    </section>

    <section class="panel" style="margin-top: 16px;">
        <h2>Historico de locacoes</h2>
        <table>
            <thead>
                <tr>
                    <th>Periodo</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($veiculo->locacoes as $locacao)
                    <tr>
                        <td>{{ $locacao->data_retirada->format('d/m/Y') }} a {{ $locacao->data_devolucao_prevista->format('d/m/Y') }}</td>
                        <td>{{ $locacao->cliente->nome }}</td>
                        <td><span class="badge info">{{ $locacao->status_label }}</span></td>
                        <td>R$ {{ number_format((float) $locacao->valor_total_previsto, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">Nenhuma locacao vinculada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
