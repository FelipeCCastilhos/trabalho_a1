<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove tabelas antigas do esqueleto Laravel que nao fazem parte da locadora.
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }

    public function down(): void
    {
        // A aplicacao nao usa autenticacao, entao o rollback nao recria essas tabelas.
    }
};
