<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Locacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $busca = trim((string) $request->query('busca', ''));

        // Busca simples pesquisando campos comuns do cliente.
        $clientes = Cliente::query()
            ->withCount([
                'locacoes',
                'locacoes as locacoes_ativas_count' => fn ($query) => $query->whereIn('status', Locacao::STATUS_ATIVOS),
            ])
            ->when($busca !== '', function ($query) use ($busca) {
                $query->where(function ($query) use ($busca) {
                    $query->where('nome', 'like', "%{$busca}%")
                        ->orWhere('cpf', 'like', "%{$busca}%")
                        ->orWhere('email', 'like', "%{$busca}%")
                        ->orWhere('telefone', 'like', "%{$busca}%");
                });
            })
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', compact('clientes', 'busca'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['ativo'] = $request->boolean('ativo');

        Cliente::create($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso.');
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load(['locacoes.veiculo']);

        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $data = $this->validatedData($request, $cliente);
        $data['ativo'] = $request->boolean('ativo');

        $cliente->update($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        // Regra de negocio: nao apagar cliente que ja tem historico de locacoes.
        if ($cliente->locacoes()->exists()) {
            return back()->with('error', 'Nao e possivel excluir um cliente com locacoes vinculadas.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente excluido com sucesso.');
    }

    private function validatedData(Request $request, ?Cliente $cliente = null): array
    {
        $idadeMinima = now()->subYears(18)->toDateString();

        // Regras unique separadas para funcionar corretamente em criacao e edicao.
        $cpfUnique = Rule::unique('clientes', 'cpf');
        $emailUnique = Rule::unique('clientes', 'email');
        $cnhUnique = Rule::unique('clientes', 'cnh');

        if ($cliente) {
            $cpfUnique->ignore($cliente->id);
            $emailUnique->ignore($cliente->id);
            $cnhUnique->ignore($cliente->id);
        }

        // Normaliza dados antes de validar e salvar.
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
            'cnh' => strtoupper(trim((string) $request->input('cnh'))),
        ]);

        return $request->validate([
            'nome' => ['required', 'string', 'min:3', 'max:120'],
            'cpf' => ['required', 'string', 'max:14', $cpfUnique],
            'email' => ['required', 'string', 'max:120', 'regex:/^[^@\s]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $emailUnique],
            'telefone' => ['required', 'string', 'max:20'],
            'cnh' => ['required', 'string', 'max:20', $cnhUnique],
            'data_nascimento' => ['required', 'date', "before_or_equal:{$idadeMinima}"],
            'ativo' => ['nullable', 'boolean'],
        ], [
            'data_nascimento.before_or_equal' => 'O cliente deve ter pelo menos 18 anos.',
            'email.regex' => 'Informe um email com dominio e extensao, como nome@gmail.com.',
            'email.required' => 'O email e obrigatorio.',
            'email.unique' => 'Este email ja esta cadastrado para outro cliente.',
            'cnh.unique' => 'Esta CNH ja esta cadastrada para outro cliente.',
        ]);
    }
}
