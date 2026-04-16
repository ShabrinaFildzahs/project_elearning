<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $table = 'forum';
    protected $fillable = ['id_pemetaan_akademik', 'id_pembuat', 'tipe_pembuat', 'judul', 'konten'];

    public function pemetaanAkademik()
    {
        return $this->belongsTo(PemetaanAkademik::class, 'id_pemetaan_akademik');
    }

    /**
     * Get the creator of the forum (Guru or Siswa).
     */
    public function pembuat()
    {
        return $this->morphTo(null, 'tipe_pembuat', 'id_pembuat');
    }

    public function komentar()
    {
        return $this->hasMany(KomentarForum::class, 'id_forum');
    }
}
