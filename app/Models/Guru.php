<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guru extends Authenticatable
{
    use Notifiable;

    protected $table = 'guru';

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'email',
        'password',
        'pendidikan_terakhir',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pemetaanAkademik()
    {
        return $this->hasMany(PemetaanAkademik::class, 'id_guru');
    }
}
