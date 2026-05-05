<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Limpeza do esqueleto Laravel: o projeto nao tem tela de login,
        // entao essas tabelas de usuario/reset nao sao utilizadas.
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }

    public function down(): void
    {
        // Como autenticacao nao faz parte do escopo, o rollback nao recria essas tabelas.
    }
};
