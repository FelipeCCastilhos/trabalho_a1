@extends('layouts.app')

@section('title', 'Dashboard - Locadora Prendatta')

@section('content')
    {{-- Tela inicial exigida no trabalho: resume a situacao operacional da locadora. --}}
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p class="muted">Resumo operacional da locadora.</p>
        </div>
        <div class="actions">
            <a class="btn primary" href="{{ route('locacoes.create') }}">Nova locacao</a>
        </div>
    </div>

    {{-- Cards de indicadores tornam os totais principais visiveis sem abrir listagens. --}}
    <section class="grid stats">
        <div class="stat">
            <span class="muted">Clientes ativos</span>
            <span class="stat-value">{{ $stats['clientes_ativos'] }}</span>
        </div>
        <div class="stat">
            <span class="muted">Veiculos cadastrados</span>
            <span class="stat-value">{{ $stats['total_veiculos'] }}</span>
        </div>
        <div class="stat">
            <span class="muted">Veiculos disponiveis</span>
            <span class="stat-value">{{ $stats['veiculos_disponiveis'] }}</span>
        </div>
        <div class="stat">
            <span class="muted">Locacoes ativas</span>
            <span class="stat-value">{{ $stats['locacoes_ativas'] }}</span>
        </div>
        <div class="stat">
            <span class="muted">Receita finalizada</span>
            <span class="stat-value">R$ {{ number_format((float) $stats['receita_finalizada'], 2, ',', '.') }}</span>
        </div>
    </section>

    <div class="grid two">
        {{-- Lista curta para acompanhar movimentacoes recentes sem poluir o dashboard. --}}
        <section class="panel">
            <h2>Locacoes recentes</h2>
            @if ($locacoesRecentes->isEmpty())
                <div class="empty">Nenhuma locacao cadastrada.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Veiculo</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locacoesRecentes as $locacao)
                            <tr>
                                <td>{{ $locacao->cliente->nome }}</td>
                                <td>{{ $locacao->veiculo->placa }} - {{ $locacao->veiculo->modelo }}</td>
                                <td><span class="badge info">{{ $locacao->status_label }}</span></td>
                                <td>R$ {{ number_format((float) $locacao->valor_total_previsto, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>

        {{-- Ajuda a localizar devolucoes vencendo ou atrasadas, que exigem acao da locadora. --}}
        <section class="panel">
            <h2>Devolucoes pendentes</h2>
            @if ($devolucoesPendentes->isEmpty())
                <div class="empty">Nenhuma devolucao vencendo hoje ou em atraso.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Veiculo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($devolucoesPendentes as $locacao)
                            <tr>
                                <td>{{ $locacao->data_devolucao_prevista->format('d/m/Y') }}</td>
                                <td>{{ $locacao->cliente->nome }}</td>
                                <td>{{ $locacao->veiculo->placa }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
@endsection
