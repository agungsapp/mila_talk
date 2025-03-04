<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PercobaanKuis extends Model
{
    //
    protected $fillable = ['id_mahasiswa', 'id_kuis', 'skor', 'status', 'dicoba_pada'];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'id_kuis');
    }
}
