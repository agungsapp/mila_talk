<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jeda extends Model
{
    //
    protected $fillable = ['id_mahasiswa', 'id_kuis', 'jeda_sampai'];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'id_kuis');
    }
}
