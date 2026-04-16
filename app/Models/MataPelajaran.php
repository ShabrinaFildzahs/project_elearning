<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $fillable = ['nama'];

    public function pemetaanAkademik()
    {
        return $this->hasMany(PemetaanAkademik::class, 'id_mata_pelajaran');
    }
}
