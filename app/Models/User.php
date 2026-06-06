<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Perfis suportados pelo sistema. Eles sao usados no menu, middleware e controllers.
    public const PROFILE_ADMIN = 'admin';
    public const PROFILE_ATENDENTE = 'atendente';

    public const PROFILE_LABELS = [
        self::PROFILE_ADMIN => 'Administrador',
        self::PROFILE_ATENDENTE => 'Atendente',
    ];

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile',
        'telefone',
        'ativo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            // Converte 0/1 do SQLite em boolean no PHP.
            'ativo' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        // Helper usado nas views e controllers para deixar a autorizacao mais legivel.
        return $this->profile === self::PROFILE_ADMIN;
    }

    public function isAtendente(): bool
    {
        // Helper especifico para regra de atendente nao cancelar locacao.
        return $this->profile === self::PROFILE_ATENDENTE;
    }
}
