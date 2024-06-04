<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    public function work()
    {
        return $this->hasMany('App\Models\Work');
    }

    public function percentage()
    {
        return $this->hasMany(Percentages::class);
    }
    
}
