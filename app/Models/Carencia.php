<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carencia extends Model
{
    protected $table = 'carencias';
    protected $connection = 'grupoamerica';
    protected $fillable = [
        'plano_id',
        'tabela_origens_id',
        'tempo',
        'detalhe',
        'frase'
    ];

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function tabelaOrigem()
    {
        return $this->belongsTo(TabelaOrigem::class, 'tabela_origens_id');
    }
}
