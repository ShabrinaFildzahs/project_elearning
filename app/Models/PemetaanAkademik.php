<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemetaanAkademik extends Model
{
    protected $table = 'pemetaan_akademik';
    protected $fillable = ['id_kelas', 'id_mata_pelajaran', 'id_guru'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_pemetaan_akademik');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'id_pemetaan_akademik');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_pemetaan_akademik');
    }

    public function forum()
    {
        return $this->hasMany(Forum::class, 'id_pemetaan_akademik');
    }
}
