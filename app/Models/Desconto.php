<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desconto extends Model
{
    protected $table = 'desconto';

    protected $fillable = [
        'tabela_origens_id',
        'plano_id',
        'valor',
    ];


    public function cidade()
    {
        return $this->belongsTo(TabelaOrigens::class, 'tabela_origens_id');
    }

    // Relação com Plano
    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }

}
