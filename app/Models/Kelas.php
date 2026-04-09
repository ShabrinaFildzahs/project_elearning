<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'classes';
    protected $fillable = ['name'];

    public function academicMaps() { return $this->hasMany(AcademicMap::class, 'class_id'); }
}
