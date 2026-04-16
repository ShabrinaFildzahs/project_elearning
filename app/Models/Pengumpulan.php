<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumpulan extends Model
{
    protected $table = 'pengumpulan';
    protected $fillable = ['id_tugas', 'id_siswa', 'path_file', 'status', 'nilai'];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'id_tugas');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
