@extends('layouts.app')

@section('title', 'Veiculos - Locadora Prendatta')

@section('content')
    {{-- Listagem da frota: permite consultar carros e acessar acoes do CRUD. --}}
    <div class="page-header">
        <div>
            <h1>Veiculos</h1>
            <p class="muted">Frota disponivel para reservas e locacoes.</p>
        </div>
        <div class="actions">
            <a class="btn primary" href="{{ route('veiculos.create') }}">Novo veiculo</a>
        </div>
    </div>

    {{-- Busca por atributos visiveis do carro, como placa, marca e modelo. --}}
    <div class="toolbar">
        <form class="search" method="GET" action="{{ route('veiculos.index') }}">
            <input type="search" name="busca" value="{{ $busca }}" placeholder="Buscar por marca, modelo, placa ou categoria">
            <button class="btn secondary" type="submit">Buscar</button>
            @if ($busca !== '')
                <a class="btn secondary" href="{{ route('veiculos.index') }}">Limpar</a>
            @endif
        </form>
    </div>

    {{-- Status aparece em destaque porque define se o veiculo pode ser locado. --}}
    <section class="panel">
        <table>
            <thead>
                <tr>
                    <th>Veiculo</th>
                    <th>Placa</th>
                    <th>Categoria</th>
                    <th>Diaria</th>
                    <th>Status</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($veiculos as $veiculo)
                    <tr>
                        <td>{{ $veiculo->marca }} {{ $veiculo->modelo }}<br><span class="muted">{{ $veiculo->ano }}</span></td>
                        <td>{{ $veiculo->placa }}</td>
                        <td>{{ \App\Models\Veiculo::CATEGORIAS[$veiculo->categoria] ?? $veiculo->categoria }}</td>
                        <td>R$ {{ number_format((float) $veiculo->valor_diaria, 2, ',', '.') }}</td>
                        <td>
                            <span @class([
                                'badge',
                                'ok' => $veiculo->status === \App\Models\Veiculo::STATUS_DISPONIVEL,
                                'warn' => $veiculo->status === \App\Models\Veiculo::STATUS_MANUTENCAO,
                                'danger' => $veiculo->status === \App\Models\Veiculo::STATUS_INATIVO,
                            ])>
                                {{ \App\Models\Veiculo::STATUS_LABELS[$veiculo->status] ?? $veiculo->status }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary" href="{{ route('veiculos.show', $veiculo) }}">Ver</a>
                                <a class="btn secondary" href="{{ route('veiculos.edit', $veiculo) }}">Editar</a>
                                <form method="POST" action="{{ route('veiculos.destroy', $veiculo) }}" onsubmit="return confirm('Excluir este veiculo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">Nenhum veiculo encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $veiculos->links() }}
        </div>
    </section>
@endsection
