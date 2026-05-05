@extends('layouts.app')

@section('title', 'Cliente - Locadora Prendatta')

@section('content')
    {{-- Tela de detalhe: concentra os dados do cliente e o historico de locacoes relacionadas. --}}
    <div class="page-header">
        <div>
            <h1>{{ $cliente->nome }}</h1>
            <p class="muted">Detalhes do cliente.</p>
        </div>
        <div class="actions">
            <a class="btn secondary" href="{{ route('clientes.edit', $cliente) }}">Editar</a>
            <a class="btn secondary" href="{{ route('clientes.index') }}">Voltar</a>
        </div>
    </div>

    <section class="panel">
        <div class="detail-list">
            <div class="detail-item"><strong>CPF</strong>{{ $cliente->cpf }}</div>
            <div class="detail-item"><strong>CNH</strong>{{ $cliente->cnh }}</div>
            <div class="detail-item"><strong>Email</strong>{{ $cliente->email ?: 'Nao informado' }}</div>
            <div class="detail-item"><strong>Telefone</strong>{{ $cliente->telefone }}</div>
            <div class="detail-item"><strong>Nascimento</strong>{{ $cliente->data_nascimento->format('d/m/Y') }}</div>
            <div class="detail-item"><strong>Status</strong>{{ $cliente->ativo ? 'Ativo' : 'Inativo' }}</div>
        </div>
    </section>

    {{-- Historico mostra o relacionamento 1:N entre cliente e locacoes. --}}
    <section class="panel" style="margin-top: 16px;">
        <h2>Locacoes do cliente</h2>
        <table>
            <thead>
                <tr>
                    <th>Periodo</th>
                    <th>Veiculo</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cliente->locacoes as $locacao)
                    <tr>
                        <td>{{ $locacao->data_retirada->format('d/m/Y') }} a {{ $locacao->data_devolucao_prevista->format('d/m/Y') }}</td>
                        <td>{{ $locacao->veiculo->placa }} - {{ $locacao->veiculo->modelo }}</td>
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
