<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolhaMes extends Model
{
    use HasFactory;

    protected $connection = 'tenant';

    protected $table = 'folha_mes';



}
