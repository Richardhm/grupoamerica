<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolhaPagamento extends Model
{
    use HasFactory;
    protected $connection = 'tenant';
    protected $table = 'folha_pagamento';



}
