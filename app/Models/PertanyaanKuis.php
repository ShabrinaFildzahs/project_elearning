<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PertanyaanKuis extends Model
{
    protected $table = 'pertanyaan_kuis';
    protected $fillable = ['id_kuis', 'pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'jawaban_benar', 'poin'];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'id_kuis');
    }
}
