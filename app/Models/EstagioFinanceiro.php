<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstagioFinanceiro extends Model
{
    protected $connection = 'tenant';
    protected $table = 'estagio_financeiros';
    protected $fillable = ['nome'];
}
