<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteEstagiario extends Model
{
    protected $connection = 'tenant';
    protected $table = 'cliente_estagiario';

    protected $fillable = ['cliente_id', 'user_id', 'data'];

    // Relacionamentos
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
