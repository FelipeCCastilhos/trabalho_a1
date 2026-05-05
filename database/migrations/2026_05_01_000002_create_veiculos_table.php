<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Entidade "Veiculo": representa cada carro da frota da locadora.
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            // Marca/modelo/ano identificam o carro para o usuario.
            $table->string('marca');
            $table->string('modelo');
            $table->unsignedSmallInteger('ano');
            // Placa unica evita cadastrar o mesmo carro duas vezes.
            $table->string('placa', 7)->unique();
            // Categoria ajuda a organizar a frota e pode ser usada em filtros.
            $table->string('categoria', 30);
            // Valor base usado para calcular o total previsto da locacao.
            $table->decimal('valor_diaria', 10, 2);
            // Status controla regra de negocio: somente "disponivel" pode ser locado.
            $table->string('status', 20)->default('disponivel');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
