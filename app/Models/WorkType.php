<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    use HasFactory;

    protected $table = 'work_types';
    public $timestamps = false; // Desactivar timestamps

    public function percentages()
    {
        return $this->hasMany(Percentages::class, 'work_type_id');
    }

    //Mutadores
    public function getNameAttribute($name)
    {
        return ucfirst($name);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }
}
