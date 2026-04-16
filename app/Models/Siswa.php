<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use Notifiable;

    protected $table = 'siswa';

    protected $fillable = [
        'id_kelas',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'email',
        'password',
        'no_hp',
        'nisn',
        'tahun_masuk',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function pengumpulan()
    {
        return $this->hasMany(Pengumpulan::class, 'id_siswa');
    }
}
