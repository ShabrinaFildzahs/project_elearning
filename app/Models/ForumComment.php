<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    protected $fillable = ['forum_id', 'user_id', 'content'];

    public function forum() { return $this->belongsTo(Forum::class); }
    public function user() { return $this->belongsTo(User::class); }
}
