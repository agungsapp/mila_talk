<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    //
    protected $fillable = ['id_kuis', 'tipe', 'konten', 'jawaban_benar'];
    protected $casts = ['konten' => 'array']; // JSON di-cast ke array

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'id_kuis');
    }
}
