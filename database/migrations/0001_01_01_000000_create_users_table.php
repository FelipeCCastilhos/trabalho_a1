<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Esta tabela e usada pelo Laravel para guardar sessoes no SQLite,
        // conforme SESSION_DRIVER=database no .env. Mesmo sem login, ela
        // permite manter mensagens de sucesso/erro entre uma tela e outra.
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // user_id fica nulo porque o projeto nao tem autenticacao.
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            // payload armazena os dados serializados da sessao.
            $table->longText('payload');
            // last_activity ajuda o Laravel a expirar sessoes antigas.
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
