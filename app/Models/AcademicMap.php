<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicMap extends Model
{
    protected $fillable = ['class_id', 'subject_id', 'teacher_id'];

    public function class() { return $this->belongsTo(Kelas::class, 'class_id'); }
    public function subject() { return $this->belongsTo(Subject::class, 'subject_id'); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function schedules() { return $this->hasMany(Schedule::class); }
    public function materials() { return $this->hasMany(Material::class); }
    public function assignments() { return $this->hasMany(Assignment::class); }
    public function forums() { return $this->hasMany(Forum::class); }
}
