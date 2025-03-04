<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    //
    protected $fillable = ['nama', 'id_dosen', 'deskripsi'];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    public function mahasiswa()
    {
        return $this->belongsToMany(User::class, 'kelas_mahasiswa', 'id_kelas', 'id_mahasiswa');
    }

    public function kuis()
    {
        return $this->hasMany(Kuis::class, 'id_kelas');
    }
}
