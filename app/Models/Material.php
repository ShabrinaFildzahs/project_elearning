<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['academic_map_id', 'title', 'description', 'file_path'];

    public function academicMap() { return $this->belongsTo(AcademicMap::class); }
}
