<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acomodacao extends Model
{
    protected $connection = 'grupoamerica'; // Conexão com o banco principal

    protected $table = "acomodacoes";
    protected $fillable = ['nome'];
}
