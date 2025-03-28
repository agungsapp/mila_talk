<?php

namespace App\Http\Controllers;

use App\Models\JawabanSiswa;
use App\Models\Soal;
use Illuminate\Http\Request;

class JawabanController extends Controller
{
    public function submitJawaban(Request $request, $id)
    {
        $soal = Soal::findOrFail($id);
        $user = $request->user();

        $request->validate([
            'jawaban' => 'required',
        ]);

        // Simpan jawaban siswa
        $jawabanSiswa = JawabanSiswa::create([
            'user_id' => $user->id,
            'soal_id' => $id,
            'jawaban' => $request->jawaban,
            'benar' => $request->jawaban === $soal->jawaban_benar,
        ]);

        return response()->json($jawabanSiswa, 201);
    }
}
