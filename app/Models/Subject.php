<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name'];

    public function academicMaps() { return $this->hasMany(AcademicMap::class, 'subject_id'); }
}
