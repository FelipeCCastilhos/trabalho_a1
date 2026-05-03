<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Locacao;
use App\Models\Veiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Dados iniciais para a avaliacao abrir o sistema ja com cadastros reais.
        $ana = Cliente::firstOrCreate(
            ['cpf' => '123.456.789-10'],
            [
                'nome' => 'Ana Souza',
                'email' => 'ana.souza@example.com',
                'telefone' => '(11) 98888-1111',
                'cnh' => 'CNH123456',
                'data_nascimento' => '1992-04-12',
                'ativo' => true,
            ],
        );

        $bruno = Cliente::firstOrCreate(
            ['cpf' => '987.654.321-00'],
            [
                'nome' => 'Bruno Lima',
                'email' => 'bruno.lima@example.com',
                'telefone' => '(21) 97777-2222',
                'cnh' => 'CNH987654',
                'data_nascimento' => '1987-09-03',
                'ativo' => true,
            ],
        );

        // A frota inicial cobre status disponivel e manutencao para demonstrar regras.
        $civic = Veiculo::firstOrCreate(
            ['placa' => 'ABC1D23'],
            [
                'marca' => 'Honda',
                'modelo' => 'Civic',
                'ano' => 2022,
                'categoria' => 'sedan',
                'valor_diaria' => 180.00,
                'status' => Veiculo::STATUS_DISPONIVEL,
            ],
        );

        $renegade = Veiculo::firstOrCreate(
            ['placa' => 'DEF4G56'],
            [
                'marca' => 'Jeep',
                'modelo' => 'Renegade',
                'ano' => 2023,
                'categoria' => 'suv',
                'valor_diaria' => 230.00,
                'status' => Veiculo::STATUS_DISPONIVEL,
            ],
        );

        Veiculo::firstOrCreate(
            ['placa' => 'GHI7890'],
            [
                'marca' => 'Fiat',
                'modelo' => 'Argo',
                'ano' => 2021,
                'categoria' => 'economico',
                'valor_diaria' => 120.00,
                'status' => Veiculo::STATUS_MANUTENCAO,
            ],
        );

        // Locacoes de exemplo mostram uma reserva ativa e uma locacao finalizada.
        Locacao::firstOrCreate(
            [
                'cliente_id' => $ana->id,
                'veiculo_id' => $civic->id,
                'data_retirada' => now()->addDay()->toDateString(),
            ],
            [
                'data_devolucao_prevista' => now()->addDays(4)->toDateString(),
                'valor_diaria' => $civic->valor_diaria,
                'valor_total_previsto' => 3 * (float) $civic->valor_diaria,
                'status' => Locacao::STATUS_RESERVADA,
                'observacoes' => 'Cliente solicitou retirada pela manha.',
            ],
        );

        Locacao::firstOrCreate(
            [
                'cliente_id' => $bruno->id,
                'veiculo_id' => $renegade->id,
                'data_retirada' => now()->subDays(6)->toDateString(),
            ],
            [
                'data_devolucao_prevista' => now()->subDays(3)->toDateString(),
                'data_devolucao_real' => now()->subDays(3)->toDateString(),
                'valor_diaria' => $renegade->valor_diaria,
                'valor_total_previsto' => 3 * (float) $renegade->valor_diaria,
                'status' => Locacao::STATUS_FINALIZADA,
                'observacoes' => 'Locacao finalizada sem pendencias.',
            ],
        );
    }
}
