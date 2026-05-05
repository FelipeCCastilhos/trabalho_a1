<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Entidade "Locacao": tabela central que liga cliente e veiculo.
        // Ela registra o periodo de uso e os valores calculados no momento da reserva.
        Schema::create('locacoes', function (Blueprint $table) {
            $table->id();
            // Relacionamentos obrigatorios: toda locacao precisa de um cliente e um veiculo.
            // restrictOnDelete preserva o historico e impede excluir cadastros vinculados.
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('veiculo_id')->constrained('veiculos')->restrictOnDelete();
            // Datas usadas para validar conflito de periodo do mesmo veiculo.
            $table->date('data_retirada');
            $table->date('data_devolucao_prevista');
            // Fica nula enquanto a locacao nao foi finalizada.
            $table->date('data_devolucao_real')->nullable();
            // Valores sao gravados na locacao para manter historico mesmo se a diaria do carro mudar.
            $table->decimal('valor_diaria', 10, 2);
            $table->decimal('valor_total_previsto', 10, 2);
            // Status controla o ciclo de vida: reservada, em andamento, finalizada ou cancelada.
            $table->string('status', 20)->default('reservada');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            // Indices ajudam nas consultas mais importantes das regras de negocio.
            $table->index(['veiculo_id', 'data_retirada', 'data_devolucao_prevista']);
            $table->index(['cliente_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locacoes');
    }
};
