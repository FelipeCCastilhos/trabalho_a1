<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela central que relaciona cliente e veiculo em um periodo.
        Schema::create('locacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('veiculo_id')->constrained('veiculos')->restrictOnDelete();
            $table->date('data_retirada');
            $table->date('data_devolucao_prevista');
            $table->date('data_devolucao_real')->nullable();
            $table->decimal('valor_diaria', 10, 2);
            $table->decimal('valor_total_previsto', 10, 2);
            $table->string('status', 20)->default('reservada');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            // Indices ajudam nas consultas de conflito de datas e limite por cliente.
            $table->index(['veiculo_id', 'data_retirada', 'data_devolucao_prevista']);
            $table->index(['cliente_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locacoes');
    }
};
