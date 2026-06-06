<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Usuarios autenticam no sistema e recebem um perfil de acesso.
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // Nome e email identificam o usuario nas telas e no login.
            $table->string('name');
            $table->string('email')->unique();
            // Senha armazenada como hash via Hash::make no AuthController.
            $table->string('password');
            // profile controla o menu, middlewares e regras de autorizacao.
            $table->string('profile', 20)->default('atendente');
            $table->string('telefone', 20)->nullable();
            // Usuario inativo existe no historico, mas nao consegue logar.
            $table->boolean('ativo')->default(true);
            // Necessario para a opcao "Manter conectado" do login.
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
