<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    // Nome explicito da tabela para evitar pluralizacao incorreta do Eloquent.
    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'telefone',
        'cnh',
        'data_nascimento',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'ativo' => 'boolean',
        ];
    }

    public function locacoes(): HasMany
    {
        // Um cliente pode ter varias locacoes ao longo do tempo.
        return $this->hasMany(Locacao::class);
    }
}
