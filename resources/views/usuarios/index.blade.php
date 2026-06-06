@extends('layouts.app')

@section('title', 'Usuarios - Locadora Prendatta')

@section('content')
    {{-- Tela administrativa: somente admin acessa a lista de usuarios cadastrados. --}}
    <div class="page-header">
        <div>
            <h1>Usuarios</h1>
            <p class="muted">Controle de acessos e perfis do sistema.</p>
        </div>
        <div class="actions">
            <a class="btn primary" href="{{ route('register') }}">Novo usuario</a>
        </div>
    </div>

    <div class="toolbar">
        <form class="search" method="GET" action="{{ route('usuarios.index') }}">
            <input type="search" name="busca" value="{{ $busca }}" placeholder="Buscar por nome, email ou perfil">
            <button class="btn secondary" type="submit">Buscar</button>
            @if ($busca !== '')
                <a class="btn secondary" href="{{ route('usuarios.index') }}">Limpar</a>
            @endif
        </form>
    </div>

    <section class="panel">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Perfil</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->telefone ?: 'Nao informado' }}</td>
                        <td>{{ \App\Models\User::PROFILE_LABELS[$usuario->profile] ?? $usuario->profile }}</td>
                        <td>
                            <span @class(['badge', 'ok' => $usuario->ativo, 'danger' => ! $usuario->ativo])>
                                {{ $usuario->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">Nenhum usuario encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $usuarios->links() }}
        </div>
    </section>
@endsection
