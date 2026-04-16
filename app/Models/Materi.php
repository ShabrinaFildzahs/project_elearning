<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';
    protected $fillable = ['id_pemetaan_akademik', 'judul', 'deskripsi', 'path_file'];

    public function pemetaanAkademik()
    {
        return $this->belongsTo(PemetaanAkademik::class, 'id_pemetaan_akademik');
    }
}
