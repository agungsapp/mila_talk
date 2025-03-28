<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function show($id)
    {
        $soal = Soal::with(['kuis', 'kuis.kelas'])->findOrFail($id);
        return response()->json($soal);
    }

    public function getSoalByKuis($id)
    {
        $soal = Soal::where('id_kuis', $id)->get();
        return response()->json($soal);
    }
}
