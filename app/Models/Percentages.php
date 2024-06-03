<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percentages extends Model
{
    use HasFactory;

    protected $table = 'percentages';
    protected $guarded = [];

    public function teacher()
    {
        return $this->BelongsTo(Teacher::class);
    }

    public function workType()
    {
        return $this->belongsTo(WorkType::class, 'work_type_id');
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
