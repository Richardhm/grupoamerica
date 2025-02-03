<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdministradoraPlano extends Model
{
    protected $table = 'administradora_planos';
    protected $connection = 'grupoamerica';
    protected $fillable = [
        'plano_id',
        'administradora_id',
        'tabela_origens_id'
    ];

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function administradora()
    {
        return $this->belongsTo(Administradora::class);
    }

    public function tabelaOrigem()
    {
        return $this->belongsTo(TabelaOrigem::class, 'tabela_origens_id');
    }
}
