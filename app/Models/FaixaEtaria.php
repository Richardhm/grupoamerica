<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaixaEtaria extends Model
{
    protected $connection = 'grupoamerica'; // Conexão com o banco principal

    protected $fillable = ['nome'];
}
