<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $fillable = ['id_pemetaan_akademik', 'hari', 'jam_mulai', 'jam_selesai'];

    public function pemetaanAkademik()
    {
        return $this->belongsTo(PemetaanAkademik::class, 'id_pemetaan_akademik');
    }
}
