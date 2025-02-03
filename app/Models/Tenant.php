<?php

namespace App\Models;

use Spatie\Multitenancy\Models\Tenant as BaseTenant;


class Tenant extends BaseTenant
{


    protected $fillable = [
        'id', // Se você usa UUID, inclua aqui
        'name',
        'domain',
        'database',
        'data', // Se você armazena configurações extras em JSON
    ];

    protected $casts = [
        'data' => 'array', // Para armazenar JSON como array
    ];
}
