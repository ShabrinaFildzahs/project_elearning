<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama'];

    public function pemetaanAkademik()
    {
        return $this->hasMany(PemetaanAkademik::class, 'id_kelas');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }
}
