<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove tabelas antigas do esqueleto Laravel antes de recriar users
        // com os campos corretos de perfil, telefone e ativo na migration seguinte.
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }

    public function down(): void
    {
        // Nao recria a estrutura antiga porque ela foi substituida pela migration nova de users.
    }
};
