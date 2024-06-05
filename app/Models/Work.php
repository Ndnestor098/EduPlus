<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Definir la relación con WorkStudent
    public function students()
    {
        return $this->hasMany(WorkStudent::class);
    }

    // Definir la relación con WorkType
    public function workType()
    {
        return $this->belongsTo(WorkType::class, 'work_type_id');
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
            return $query->orderBy('course');
    }

    public function scopeToday($query)
    {
        return $query->where('deliver', '>', Carbon::today());
    }

    public function scopeSubject($query, $subject)
    {
        if($subject)
            return $query->where('subject', $subject);
    }

    public function scopePublic($query)
    {
        return $query->where('public', true);
    }
}
