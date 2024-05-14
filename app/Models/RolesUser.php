<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolesUser extends Model
{
    use HasFactory;
    
    protected $table = "roles_users";

    protected $fillable = ['user_id', 'role_id'];

    public function user()
    {
        return $this->BelongsTo('App\Models\User');
    }

    public function role()
    {
        return $this->BelongsTo('App\Models\Role');
    }
}
