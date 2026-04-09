<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['academic_map_id', 'day', 'start_time', 'end_time'];

    public function academicMap() { return $this->belongsTo(AcademicMap::class); }
}
