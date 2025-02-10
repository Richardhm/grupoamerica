<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $connection = 'tenant';
    protected $table = 'cargos';

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'permission_cargos','cargo_id','permission_id');
    }
}
