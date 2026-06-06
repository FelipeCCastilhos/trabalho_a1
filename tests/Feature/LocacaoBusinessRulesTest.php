<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Locacao;
use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LocacaoBusinessRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_veiculo_nao_pode_ser_locado_em_periodo_conflitante(): void
    {
        $this->actingAs($this->admin());

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
        $this->actingAs($this->admin());

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

    public function test_atendente_nao_acessa_rotas_de_veiculos(): void
    {
        $this->actingAs($this->atendente());

        $response = $this->get(route('veiculos.index'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'Acesso restrito a administradores.');
    }

    public function test_atendente_nao_pode_cancelar_locacao(): void
    {
        $this->actingAs($this->atendente());

        $cliente = $this->cliente();
        $veiculo = $this->veiculo();

        $locacao = Locacao::create([
            'cliente_id' => $cliente->id,
            'veiculo_id' => $veiculo->id,
            'data_retirada' => now()->addDay()->toDateString(),
            'data_devolucao_prevista' => now()->addDays(3)->toDateString(),
            'valor_diaria' => 150,
            'valor_total_previsto' => 300,
            'status' => Locacao::STATUS_RESERVADA,
        ]);

        $response = $this->put(route('locacoes.update', $locacao), [
            'cliente_id' => $cliente->id,
            'veiculo_id' => $veiculo->id,
            'data_retirada' => now()->addDay()->toDateString(),
            'data_devolucao_prevista' => now()->addDays(3)->toDateString(),
            'status' => Locacao::STATUS_CANCELADA,
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseHas('locacoes', [
            'id' => $locacao->id,
            'status' => Locacao::STATUS_RESERVADA,
        ]);
    }

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin Teste',
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'profile' => User::PROFILE_ADMIN,
            'telefone' => '(11) 90000-0001',
            'ativo' => true,
        ]);
    }

    private function atendente(): User
    {
        return User::create([
            'name' => 'Atendente Teste',
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'profile' => User::PROFILE_ATENDENTE,
            'telefone' => '(11) 90000-0002',
            'ativo' => true,
        ]);
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
