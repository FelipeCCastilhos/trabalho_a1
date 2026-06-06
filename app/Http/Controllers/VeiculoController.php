<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VeiculoController extends Controller
{
    public function index(Request $request): View
    {
        $busca = trim((string) $request->query('busca', ''));

        // Permite localizar veiculos por dados visiveis na listagem.
        $veiculos = Veiculo::query()
            ->withCount('locacoes')
            ->when($busca !== '', function ($query) use ($busca) {
                $query->where(function ($query) use ($busca) {
                    $query->where('marca', 'like', "%{$busca}%")
                        ->orWhere('modelo', 'like', "%{$busca}%")
                        ->orWhere('placa', 'like', "%{$busca}%")
                        ->orWhere('categoria', 'like', "%{$busca}%");
                });
            })
            ->orderBy('marca')
            ->orderBy('modelo')
            ->paginate(10)
            ->withQueryString();

        return view('veiculos.index', compact('veiculos', 'busca'));
    }

    public function create(): View
    {
        return view('veiculos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        Veiculo::create($data);

        return redirect()->route('veiculos.index')->with('success', 'Veiculo cadastrado com sucesso.');
    }

    public function show(Veiculo $veiculo): View
    {
        $veiculo->load(['locacoes.cliente']);

        return view('veiculos.show', compact('veiculo'));
    }

    public function edit(Veiculo $veiculo): View
    {
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(Request $request, Veiculo $veiculo): RedirectResponse
    {
        $data = $this->validatedData($request, $veiculo);

        $veiculo->update($data);

        return redirect()->route('veiculos.index')->with('success', 'Veiculo atualizado com sucesso.');
    }

    public function destroy(Request $request, Veiculo $veiculo): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            return back()->with('error', 'Apenas administradores podem excluir registros.');
        }

        // Regra de negocio: preservar historico de locacoes do veiculo.
        if ($veiculo->locacoes()->exists()) {
            return back()->with('error', 'Nao e possivel excluir um veiculo com locacoes vinculadas.');
        }

        $veiculo->delete();

        return redirect()->route('veiculos.index')->with('success', 'Veiculo excluido com sucesso.');
    }

    private function validatedData(Request $request, ?Veiculo $veiculo = null): array
    {
        // Remove hifen/espacos e salva a placa em maiusculo.
        $request->merge([
            'placa' => strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $request->input('placa'))),
        ]);

        return $request->validate([
            'marca' => ['required', 'string', 'max:60'],
            'modelo' => ['required', 'string', 'max:80'],
            'ano' => ['required', 'integer', 'between:1990,'.now()->addYear()->year],
            'placa' => ['required', 'string', 'size:7', 'regex:/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/', Rule::unique('veiculos', 'placa')->ignore($veiculo)],
            'categoria' => ['required', Rule::in(array_keys(Veiculo::CATEGORIAS))],
            'valor_diaria' => ['required', 'numeric', 'min:80', 'max:2000'],
            'status' => ['required', Rule::in(array_keys(Veiculo::STATUS_LABELS))],
        ], [
            'placa.regex' => 'Informe uma placa valida no padrao ABC1234 ou ABC1D23.',
        ]);
    }
}
