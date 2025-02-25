<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingDiario extends Model
{
    use HasFactory;
    protected $table = 'ranking_diario';
    protected $connection = 'tenant';
    protected $fillable = [
        'user_id',
        'nome',
        'corretora_id',
        'vidas_individual',
        'vidas_coletivo',
        'vidas_empresarial',
        'data',
    ];
}
