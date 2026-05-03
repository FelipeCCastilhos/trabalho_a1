<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Locacao;
use App\Models\Veiculo;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $hoje = now()->toDateString();

        // Indicadores principais exibidos na tela inicial.
        $stats = [
            'clientes_ativos' => Cliente::where('ativo', true)->count(),
            'total_veiculos' => Veiculo::count(),
            'veiculos_disponiveis' => Veiculo::where('status', Veiculo::STATUS_DISPONIVEL)
                ->whereDoesntHave('locacoes', function ($query) use ($hoje) {
                    // O carro so conta como disponivel se nao estiver locado hoje.
                    $query->whereIn('status', Locacao::STATUS_ATIVOS)
                        ->whereDate('data_retirada', '<=', $hoje)
                        ->whereDate('data_devolucao_prevista', '>=', $hoje);
                })
                ->count(),
            'locacoes_ativas' => Locacao::whereIn('status', Locacao::STATUS_ATIVOS)->count(),
            'receita_finalizada' => Locacao::where('status', Locacao::STATUS_FINALIZADA)->sum('valor_total_previsto'),
        ];

        // Listas curtas para o painel nao ficar poluido.
        $locacoesRecentes = Locacao::with(['cliente', 'veiculo'])
            ->latest()
            ->limit(5)
            ->get();

        $devolucoesPendentes = Locacao::with(['cliente', 'veiculo'])
            ->whereIn('status', Locacao::STATUS_ATIVOS)
            ->whereDate('data_devolucao_prevista', '<=', $hoje)
            ->orderBy('data_devolucao_prevista')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'locacoesRecentes', 'devolucoesPendentes'));
    }
}
