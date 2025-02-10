<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValoresCorretoresLancados extends Model
{
    use HasFactory;
    protected $connection = 'tenant';
    protected $table = 'valores_corretores_lancadas';

    protected $fillable = [
        'corretora_id',
        'user_id',
        'data',
        'valor_comissao',
        'valor_total',
        'valor_desconto',
        'valor_estorno'
    ];


}
