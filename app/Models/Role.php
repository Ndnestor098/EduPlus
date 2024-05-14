<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    //Relacion uno a muchos con la tabla RolesUser
    public function RolesUser()
    {
        return $this->hasMany('App\Models\RolesUser');
    }
}
