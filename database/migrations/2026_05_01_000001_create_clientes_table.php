<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Entidade "Cliente": representa a pessoa que pode alugar um veiculo.
        // Fica separada de locacoes para reaproveitar o cadastro em varios alugueis.
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            // Dados basicos exibidos nas listagens e comprovantes da locacao.
            $table->string('nome');
            // CPF e CNH sao unicos para impedir cadastro duplicado do mesmo motorista.
            $table->string('cpf', 14)->unique();
            // Email tambem e unico e a obrigatoriedade e validada no controller.
            $table->string('email')->nullable()->unique();
            $table->string('telefone', 20);
            $table->string('cnh', 20)->unique();
            // Usada pela validacao de idade minima de 18 anos.
            $table->date('data_nascimento');
            // Cliente inativo permanece no historico, mas nao pode abrir nova locacao.
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
