<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarForum extends Model
{
    protected $table = 'komentar_forum';
    protected $fillable = ['id_forum', 'id_pembuat', 'tipe_pembuat', 'konten'];

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'id_forum');
    }

    /**
     * Get the creator of the comment (Guru or Siswa).
     */
    public function pembuat()
    {
        return $this->morphTo(null, 'tipe_pembuat', 'id_pembuat');
    }
}
