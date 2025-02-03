<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $connection = 'tenant';

    protected $table = 'clientes';

    protected $fillable = [
        'user_id',
        'nome',
        'cidade',
        'celular',
        'telefone',
        'email',
        'cpf',
        'data_nascimento',
        'cep',
        'rua',
        'bairro',
        'complemento',
        'uf',
        'cnpj',
        'pessoa_fisica',
        'pessoa_juridica',
        'codigo_externo',
        'dependente',
        'nm_plano',
        'numero_registro_plano',
        'rede_plano',
        'tipo_acomodacao_plano',
        'segmentacao_plano',
        'cateirinha',
        'quantidade_vidas',
        'dados',
        'baixa',
        'desconto_operadora',
        'quantidade_parcelas'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'pessoa_fisica' => 'boolean',
        'pessoa_juridica' => 'boolean',
        'dependente' => 'boolean',
        'dados' => 'boolean',
        'baixa' => 'date'
    ];
}
