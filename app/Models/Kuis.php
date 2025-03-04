<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    //
    protected $fillable = ['judul', 'deskripsi', 'id_kelas', 'id_dosen', 'nilai_lulus'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    public function soal()
    {
        return $this->hasMany(Soal::class, 'id_kuis');
    }

    public function percobaan()
    {
        return $this->hasMany(PercobaanKuis::class, 'id_kuis');
    }
}
