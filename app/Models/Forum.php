<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $fillable = ['academic_map_id', 'user_id', 'title', 'content'];

    public function academicMap() { return $this->belongsTo(AcademicMap::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(ForumComment::class); }
}
