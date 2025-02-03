<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, UsesTenantConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cargo_id',
        'codigo_vendedor',
        'name',
        'email',
        'ranking',
        'cpf',
        'endereco',
        'cidade',
        'estado',
        'celular',
        'numero',
        'image',
        'password',
        'admin',
        'ativo',
        'estagiario',
        'clt',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ranking' => 'boolean',
        'admin' => 'boolean',
        'ativo' => 'boolean',
        'estagiario' => 'boolean',
        'clt' => 'boolean',
    ];


    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
}
