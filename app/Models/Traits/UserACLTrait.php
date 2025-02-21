<?php

namespace App\Models\Traits;
use App\Models\Cargo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait UserACLTrait
{

    public function permissionsACL()
    {
        $pp = [];
        $permissions = $this::where("id",auth()->user()->id)->with(['cargo','cargo.permissions'])->first();
        foreach($permissions->cargo->permissions as $p) {
            array_push($pp,$p->name);
        }
        return $pp;
    }

    public function hasPermission(string $permissionName)
    {
        return in_array($permissionName,$this->permissionsACL());
    }

//    public function isAdmin()
//    {
//        return in_array($this->email,config('acl.admins'));
//    }

}

