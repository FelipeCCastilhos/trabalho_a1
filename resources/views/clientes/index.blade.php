@extends('layouts.app')

@section('title', 'Clientes - Locadora Prendatta')

@section('content')
    {{-- Listagem de clientes: representa o "Read" do CRUD e inclui busca obrigatoria. --}}
    <div class="page-header">
        <div>
            <h1>Clientes</h1>
            <p class="muted">Cadastro de motoristas habilitados para locacao.</p>
        </div>
        <div class="actions">
            <a class="btn primary" href="{{ route('clientes.create') }}">Novo cliente</a>
        </div>
    </div>

    {{-- Filtro GET preserva a busca na URL e funciona junto com a paginacao. --}}
    <div class="toolbar">
        <form class="search" method="GET" action="{{ route('clientes.index') }}">
            <input type="search" name="busca" value="{{ $busca }}" placeholder="Buscar por nome, CPF, email ou telefone">
            <button class="btn secondary" type="submit">Buscar</button>
            @if ($busca !== '')
                <a class="btn secondary" href="{{ route('clientes.index') }}">Limpar</a>
            @endif
        </form>
    </div>

    {{-- Tabela mostra dados essenciais e a quantidade de locacoes para apoiar as regras de exclusao. --}}
    <section class="panel">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Contato</th>
                    <th>Locacoes</th>
                    <th>Status</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->nome }}</td>
                        <td>{{ $cliente->cpf }}</td>
                        <td>{{ $cliente->email }}<br><span class="muted">{{ $cliente->telefone }}</span></td>
                        <td>{{ $cliente->locacoes_count }} total / {{ $cliente->locacoes_ativas_count }} ativas</td>
                        <td>
                            <span @class(['badge', 'ok' => $cliente->ativo, 'danger' => ! $cliente->ativo])>
                                {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary" href="{{ route('clientes.show', $cliente) }}">Ver</a>
                                <a class="btn secondary" href="{{ route('clientes.edit', $cliente) }}">Editar</a>
                                @if (auth()->user()?->isAdmin())
                                    <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" onsubmit="return confirm('Excluir este cliente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn danger" type="submit">Excluir</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">Nenhum cliente encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $clientes->links() }}
        </div>
    </section>
@endsection
