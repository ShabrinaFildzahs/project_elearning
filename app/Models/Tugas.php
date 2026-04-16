<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $fillable = ['id_pemetaan_akademik', 'judul', 'deskripsi', 'tenggat_waktu', 'tipe'];

    protected $casts = [
        'tenggat_waktu' => 'datetime',
    ];

    public function pemetaanAkademik()
    {
        return $this->belongsTo(PemetaanAkademik::class, 'id_pemetaan_akademik');
    }

    public function pengumpulan()
    {
        return $this->hasMany(Pengumpulan::class, 'id_tugas');
    }
}
