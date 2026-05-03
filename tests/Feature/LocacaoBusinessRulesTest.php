<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Locacao;
use App\Models\Veiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocacaoBusinessRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_veiculo_nao_pode_ser_locado_em_periodo_conflitante(): void
    {
        $cliente = $this->cliente();
        $veiculo = $this->veiculo();

        // Primeira locacao ocupa o carro no periodo informado.
        Locacao::create([
            'cliente_id' => $cliente->id,
            'veiculo_id' => $veiculo->id,
            'data_retirada' => now()->addDay()->toDateString(),
            'data_devolucao_prevista' => now()->addDays(4)->toDateString(),
            'valor_diaria' => 150,
            'valor_total_previsto' => 450,
            'status' => Locacao::STATUS_RESERVADA,
        ]);

        // Segunda tentativa cruza as datas e deve ser bloqueada.
        $response = $this->post(route('locacoes.store'), [
            'cliente_id' => $cliente->id,
            'veiculo_id' => $veiculo->id,
            'data_retirada' => now()->addDays(2)->toDateString(),
            'data_devolucao_prevista' => now()->addDays(5)->toDateString(),
            'status' => Locacao::STATUS_RESERVADA,
        ]);

        $response->assertSessionHasErrors('veiculo_id');
        $this->assertDatabaseCount('locacoes', 1);
    }

    public function test_cliente_com_locacao_vinculada_nao_pode_ser_excluido(): void
    {
        $cliente = $this->cliente();
        $veiculo = $this->veiculo();

        // Um cliente com historico de locacao nao pode ser removido.
        Locacao::create([
            'cliente_id' => $cliente->id,
            'veiculo_id' => $veiculo->id,
            'data_retirada' => now()->addDay()->toDateString(),
            'data_devolucao_prevista' => now()->addDays(3)->toDateString(),
            'valor_diaria' => 150,
            'valor_total_previsto' => 300,
            'status' => Locacao::STATUS_RESERVADA,
        ]);

        $response = $this->delete(route('clientes.destroy', $cliente));

        $response->assertRedirect();
        $this->assertDatabaseHas('clientes', ['id' => $cliente->id]);
    }

    private function cliente(): Cliente
    {
        return Cliente::create([
            'nome' => 'Cliente Teste',
            'cpf' => fake()->numerify('###.###.###-##'),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => '(11) 99999-0000',
            'cnh' => fake()->unique()->numerify('CNH######'),
            'data_nascimento' => '1990-01-01',
            'ativo' => true,
        ]);
    }

    private function veiculo(): Veiculo
    {
        return Veiculo::create([
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
            'ano' => 2022,
            'placa' => fake()->unique()->regexify('[A-Z]{3}[0-9][A-Z][0-9]{2}'),
            'categoria' => 'sedan',
            'valor_diaria' => 150,
            'status' => Veiculo::STATUS_DISPONIVEL,
        ]);
    }
}
