<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Veiculo extends Model
{
    // Nome explicito da tabela para manter o padrao em portugues.
    protected $table = 'veiculos';

    // Status usados para controlar se o carro pode entrar em uma locacao.
    public const STATUS_DISPONIVEL = 'disponivel';
    public const STATUS_MANUTENCAO = 'manutencao';
    public const STATUS_INATIVO = 'inativo';

    public const STATUS_LABELS = [
        self::STATUS_DISPONIVEL => 'Disponivel',
        self::STATUS_MANUTENCAO => 'Manutencao',
        self::STATUS_INATIVO => 'Inativo',
    ];

    public const CATEGORIAS = [
        'economico' => 'Economico',
        'sedan' => 'Sedan',
        'suv' => 'SUV',
        'pickup' => 'Pickup',
        'luxo' => 'Luxo',
    ];

    protected $fillable = [
        'marca',
        'modelo',
        'ano',
        'placa',
        'categoria',
        'valor_diaria',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'ano' => 'integer',
            'valor_diaria' => 'decimal:2',
        ];
    }

    public function locacoes(): HasMany
    {
        // Um veiculo pode aparecer em varias locacoes, desde que sem conflito de datas.
        return $this->hasMany(Locacao::class);
    }
}
