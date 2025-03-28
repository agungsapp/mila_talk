<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsToMany(User::class, 'kelas_mahasiswas', 'id_kelas', 'id_mahasiswa');
    }


    public function kuis()
    {
        return $this->hasMany(Kuis::class, 'id_kelas');
    }

    public function kelasMahasiswa(): HasMany
    {
        return $this->hasMany(KelasMahasiswa::class, 'id_mahasiswa');
    }

    // Accessor untuk deskripsi
    public function getDeskripsiAttribute($value)
    {
        return $value ?: '-';
    }
}
