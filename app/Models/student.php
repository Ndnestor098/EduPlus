<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    public function qualification()
    {
        return $this->hasOne('App\Models\Qualification');
    }

    //Scope de busquedas Query
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('name', 'LIKE', "%$name%");
    }

    public function scopeCourse($query, $course)
    {
        if($course)
            return $query->where('course', $course);
    }

    public function scopeNone($query, $none)
    {
        if(!$none)
            return $query->where('course', 1);
    }
}
