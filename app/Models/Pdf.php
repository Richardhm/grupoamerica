<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    protected $connection = 'grupoamerica';
    protected $table = 'pdf';
    protected $fillable = [
        'plano_id',
        'tabela_origens_id',
        'linha01', 'linha02', 'linha03',
        'consultas_eletivas_total', 'consultas_de_urgencia_total',
        'exames_simples_total', 'exames_complexos_total',
        'terapias_especiais_total', 'demais_terapias_total',
        'internacoes_total', 'cirurgia_total',
        'consultas_eletivas_parcial', 'consultas_de_urgencia_parcial',
        'exames_simples_parcial', 'exames_complexos_parcial',
        'terapias_especiais_parcial', 'demais_terapias_parcial',
        'internacoes_parcial', 'cirurgia_parcial'
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
