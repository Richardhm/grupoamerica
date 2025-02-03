<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabela extends Model
{
    protected $connection = 'grupoamerica'; // Banco de dados principal

    protected $fillable = [
        'administradora_id',
        'tabela_origens_id',
        'plano_id',
        'acomodacao_id',
        'faixa_etaria_id',
        'coparticipacao',
        'odonto',
        'valor',
    ];

    public function administradora()
    {
        return $this->belongsTo(Administradora::class);
    }

    public function tabelaOrigem()
    {
        return $this->belongsTo(TabelaOrigem::class, 'tabela_origens_id');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function acomodacao()
    {
        return $this->belongsTo(Acomodacao::class);
    }

    public function faixaEtaria()
    {
        return $this->belongsTo(FaixaEtaria::class);
    }
}
