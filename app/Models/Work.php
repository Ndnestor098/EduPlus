<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'scored',
        'course',
        'pdf',
        'img',
        'subject',
        'deliver',
        'teacher_id'
    ];

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher');
    }

    //Mutadores
    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $slug);
    }
}
