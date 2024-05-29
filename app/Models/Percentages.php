<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percentages extends Model
{
    use HasFactory;

    protected $table = 'percentages';

    public function teacher()
    {
        return $this->BelongsTo(Teacher::class);
    }

    public function workType()
    {
        return $this->belongsTo(WorkType::class, 'work_type_id');
    }
}
