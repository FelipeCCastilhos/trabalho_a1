<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Locacao;
use App\Models\Veiculo;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LocacaoController extends Controller
{
    public function index(Request $request): View
    {
        $busca = trim((string) $request->query('busca', ''));
        $status = $request->query('status');

        // A listagem permite buscar por cliente ou veiculo e filtrar por status.
        $locacoes = Locacao::query()
            ->with(['cliente', 'veiculo'])
            ->when($busca !== '', function ($query) use ($busca) {
                $query->where(function ($query) use ($busca) {
                    $query->whereHas('cliente', function ($query) use ($busca) {
                        $query->where('nome', 'like', "%{$busca}%")
                            ->orWhere('cpf', 'like', "%{$busca}%");
                    })->orWhereHas('veiculo', function ($query) use ($busca) {
                        $query->where('placa', 'like', "%{$busca}%")
                            ->orWhere('modelo', 'like', "%{$busca}%");
                    });
                });
            })
            ->when($status && array_key_exists($status, Locacao::STATUS_LABELS), fn ($query) => $query->where('status', $status))
            ->orderByDesc('data_retirada')
            ->paginate(10)
            ->withQueryString();

        return view('locacoes.index', compact('locacoes', 'busca', 'status'));
    }

    public function create(): View
    {
        return view('locacoes.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($this->atendenteTentouCancelar($request, $data)) {
            return back()
                ->withErrors(['status' => 'Apenas administradores podem cancelar locações.'])
                ->withInput();
        }

        $this->validateBusinessRules($data);
        $data = $this->withFinancialData($data);

        Locacao::create($data);

        return redirect()->route('locacoes.index')->with('success', 'Locacao cadastrada com sucesso.');
    }

    public function show(Locacao $locacao): View
    {
        $locacao->load(['cliente', 'veiculo']);

        return view('locacoes.show', compact('locacao'));
    }

    public function edit(Locacao $locacao): View
    {
        return view('locacoes.edit', $this->formData($locacao) + compact('locacao'));
    }

    public function update(Request $request, Locacao $locacao): RedirectResponse
    {
        $data = $this->validatedData($request, $locacao);

        if ($this->atendenteTentouCancelar($request, $data)) {
            return back()
                ->withErrors(['status' => 'Apenas administradores podem cancelar locações.'])
                ->withInput();
        }

        $this->validateBusinessRules($data, $locacao);
        $data = $this->withFinancialData($data);

        $locacao->update($data);

        return redirect()->route('locacoes.index')->with('success', 'Locacao atualizada com sucesso.');
    }

    public function destroy(Request $request, Locacao $locacao): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            return back()->with('error', 'Apenas administradores podem excluir registros.');
        }

        // Regra de negocio: locacao em andamento/finalizada deve ficar no historico.
        if (in_array($locacao->status, [Locacao::STATUS_EM_ANDAMENTO, Locacao::STATUS_FINALIZADA], true)) {
            return back()->with('error', 'Somente locacoes reservadas ou canceladas podem ser excluidas.');
        }

        $locacao->delete();

        return redirect()->route('locacoes.index')->with('success', 'Locacao excluida com sucesso.');
    }

    private function formData(?Locacao $locacao = null): array
    {
        // Formularios exibem apenas clientes ativos e veiculos disponiveis.
        $clientes = Cliente::query()
            ->where('ativo', true)
            ->when($locacao, fn ($query) => $query->orWhere('id', $locacao->cliente_id))
            ->orderBy('nome')
            ->get();

        $veiculos = Veiculo::query()
            ->where('status', Veiculo::STATUS_DISPONIVEL)
            ->when($locacao, fn ($query) => $query->orWhere('id', $locacao->veiculo_id))
            ->orderBy('marca')
            ->orderBy('modelo')
            ->get();

        return compact('clientes', 'veiculos');
    }

    private function validatedData(Request $request, ?Locacao $locacao = null): array
    {
        return $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'veiculo_id' => ['required', 'exists:veiculos,id'],
            'data_retirada' => ['required', 'date'],
            'data_devolucao_prevista' => ['required', 'date', 'after:data_retirada'],
            'data_devolucao_real' => ['nullable', 'date', 'after_or_equal:data_retirada', Rule::requiredIf($request->input('status') === Locacao::STATUS_FINALIZADA)],
            'status' => ['required', Rule::in(array_keys(Locacao::STATUS_LABELS))],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ], [
            'data_devolucao_prevista.after' => 'A devolucao prevista deve ser posterior a retirada.',
            'data_devolucao_real.required' => 'Informe a devolucao real para finalizar a locacao.',
        ]);
    }

    private function validateBusinessRules(array $data, ?Locacao $locacao = null): void
    {
        $cliente = Cliente::findOrFail($data['cliente_id']);
        $veiculo = Veiculo::findOrFail($data['veiculo_id']);
        $statusAtivo = in_array($data['status'], Locacao::STATUS_ATIVOS, true);

        $errors = [];

        // Cliente inativo nao pode iniciar nova locacao.
        if ($statusAtivo && ! $cliente->ativo) {
            $errors['cliente_id'] = 'Nao e permitido abrir locacao para cliente inativo.';
        }

        // Veiculo em manutencao ou inativo nao pode ser locado.
        if ($statusAtivo && $veiculo->status !== Veiculo::STATUS_DISPONIVEL) {
            $errors['veiculo_id'] = 'Somente veiculos disponiveis podem ser locados.';
        }

        // Evita duas locacoes ativas para o mesmo carro no mesmo periodo.
        if ($statusAtivo && $this->vehicleHasConflict($data, $locacao)) {
            $errors['veiculo_id'] = 'O veiculo ja possui locacao ativa nesse periodo.';
        }

        // Limita cada cliente a duas locacoes ativas simultaneamente.
        if ($statusAtivo && $this->clienteAtingiuLimite($data['cliente_id'], $locacao)) {
            $errors['cliente_id'] = 'O cliente ja possui 2 locacoes ativas.';
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function atendenteTentouCancelar(Request $request, array $data): bool
    {
        return $request->user()?->isAtendente()
            && $data['status'] === Locacao::STATUS_CANCELADA;
    }

    private function vehicleHasConflict(array $data, ?Locacao $locacao = null): bool
    {
        // Dois periodos conflitam quando as datas se cruzam.
        return Locacao::query()
            ->where('veiculo_id', $data['veiculo_id'])
            ->whereIn('status', Locacao::STATUS_ATIVOS)
            ->when($locacao, fn ($query) => $query->whereKeyNot($locacao->id))
            ->whereDate('data_retirada', '<=', $data['data_devolucao_prevista'])
            ->whereDate('data_devolucao_prevista', '>=', $data['data_retirada'])
            ->exists();
    }

    private function clienteAtingiuLimite(int|string $clienteId, ?Locacao $locacao = null): bool
    {
        // Ao editar, a propria locacao nao entra na contagem do limite.
        return Locacao::query()
            ->where('cliente_id', $clienteId)
            ->whereIn('status', Locacao::STATUS_ATIVOS)
            ->when($locacao, fn ($query) => $query->whereKeyNot($locacao->id))
            ->count() >= 2;
    }

    private function withFinancialData(array $data): array
    {
        $veiculo = Veiculo::findOrFail($data['veiculo_id']);
        $retirada = Carbon::parse($data['data_retirada']);
        $devolucao = Carbon::parse($data['data_devolucao_prevista']);
        $dias = max(1, (int) $retirada->diffInDays($devolucao));

        // O valor e calculado no servidor para o usuario nao manipular o total.
        $data['valor_diaria'] = $veiculo->valor_diaria;
        $data['valor_total_previsto'] = $dias * (float) $veiculo->valor_diaria;
        $data['data_devolucao_real'] = $data['data_devolucao_real'] ?? null;

        return $data;
    }
}
