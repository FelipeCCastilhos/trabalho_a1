@extends('layouts.app')

@section('title', 'Locacoes - Locadora Rota Livre')

@section('content')
    <div class="page-header">
        <div>
            <h1>Locacoes</h1>
            <p class="muted">Reservas, retiradas, devolucoes e cancelamentos.</p>
        </div>
        <div class="actions">
            <a class="btn primary" href="{{ route('locacoes.create') }}">Nova locacao</a>
        </div>
    </div>

    <div class="toolbar">
        <form class="search" method="GET" action="{{ route('locacoes.index') }}">
            <input type="search" name="busca" value="{{ $busca }}" placeholder="Buscar cliente, CPF, placa ou modelo">
            <select name="status">
                <option value="">Todos os status</option>
                @foreach (\App\Models\Locacao::STATUS_LABELS as $valor => $label)
                    <option value="{{ $valor }}" @selected($status === $valor)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn secondary" type="submit">Filtrar</button>
            @if ($busca !== '' || $status)
                <a class="btn secondary" href="{{ route('locacoes.index') }}">Limpar</a>
            @endif
        </form>
    </div>

    <section class="panel">
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Veiculo</th>
                    <th>Periodo</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($locacoes as $locacao)
                    <tr>
                        <td>{{ $locacao->cliente->nome }}<br><span class="muted">{{ $locacao->cliente->cpf }}</span></td>
                        <td>{{ $locacao->veiculo->placa }}<br><span class="muted">{{ $locacao->veiculo->marca }} {{ $locacao->veiculo->modelo }}</span></td>
                        <td>{{ $locacao->data_retirada->format('d/m/Y') }} a {{ $locacao->data_devolucao_prevista->format('d/m/Y') }}</td>
                        <td><span class="badge info">{{ $locacao->status_label }}</span></td>
                        <td>R$ {{ number_format((float) $locacao->valor_total_previsto, 2, ',', '.') }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary" href="{{ route('locacoes.show', $locacao) }}">Ver</a>
                                <a class="btn secondary" href="{{ route('locacoes.edit', $locacao) }}">Editar</a>
                                <form method="POST" action="{{ route('locacoes.destroy', $locacao) }}" onsubmit="return confirm('Excluir esta locacao?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">Nenhuma locacao encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $locacoes->links() }}
        </div>
    </section>
@endsection
