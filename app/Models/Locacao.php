<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Locacao extends Model
{
    // A palavra "locacao" nao pluraliza bem automaticamente, entao a tabela e fixa.
    protected $table = 'locacoes';

    // Status que representam o ciclo de vida de uma locacao.
    public const STATUS_RESERVADA = 'reservada';
    public const STATUS_EM_ANDAMENTO = 'em_andamento';
    public const STATUS_FINALIZADA = 'finalizada';
    public const STATUS_CANCELADA = 'cancelada';

    public const STATUS_ATIVOS = [
        self::STATUS_RESERVADA,
        self::STATUS_EM_ANDAMENTO,
    ];

    public const STATUS_LABELS = [
        self::STATUS_RESERVADA => 'Reservada',
        self::STATUS_EM_ANDAMENTO => 'Em andamento',
        self::STATUS_FINALIZADA => 'Finalizada',
        self::STATUS_CANCELADA => 'Cancelada',
    ];

    protected $fillable = [
        'cliente_id',
        'veiculo_id',
        'data_retirada',
        'data_devolucao_prevista',
        'data_devolucao_real',
        'valor_diaria',
        'valor_total_previsto',
        'status',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'data_retirada' => 'date',
            'data_devolucao_prevista' => 'date',
            'data_devolucao_real' => 'date',
            'valor_diaria' => 'decimal:2',
            'valor_total_previsto' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        // Cada locacao pertence a um unico cliente.
        return $this->belongsTo(Cliente::class);
    }

    public function veiculo(): BelongsTo
    {
        // Cada locacao reserva ou utiliza um unico veiculo.
        return $this->belongsTo(Veiculo::class);
    }

    public function getDiasPrevistosAttribute(): int
    {
        // Cobra no minimo uma diaria mesmo quando as datas estao muito proximas.
        return max(1, (int) Carbon::parse($this->data_retirada)->diffInDays(Carbon::parse($this->data_devolucao_prevista)));
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}
