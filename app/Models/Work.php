<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher');
    }

    //Mutadores
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    }

    //Scope
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
