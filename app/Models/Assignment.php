<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['academic_map_id', 'title', 'description', 'deadline', 'type'];

    public function academicMap() { return $this->belongsTo(AcademicMap::class); }
    public function submissions() { return $this->hasMany(Submission::class); }
}
