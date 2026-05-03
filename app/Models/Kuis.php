<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    protected $table = 'kuis';
    protected $fillable = ['id_pemetaan_akademik', 'judul', 'deskripsi', 'tenggat_waktu', 'durasi_menit'];

    protected $casts = [
        'tenggat_waktu' => 'datetime',
    ];

    public function pemetaanAkademik()
    {
        return $this->belongsTo(PemetaanAkademik::class, 'id_pemetaan_akademik');
    }

    public function pertanyaan()
    {
        return $this->hasMany(PertanyaanKuis::class, 'id_kuis');
    }

    public function hasil()
    {
        return $this->hasMany(HasilKuis::class, 'id_kuis');
    }
}
