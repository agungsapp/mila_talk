<?php

namespace App\Http\Controllers;

use App\Http\Resources\KelasResource;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua kelas
        $kelas = Kelas::with('dosen')->get();

        // Ambil ID mahasiswa yang login
        $mahasiswaId = $request->user()->id;

        // Tambah info is_register ke setiap kelas
        $kelas = $kelas->map(function ($item) use ($mahasiswaId) {
            $item->is_register = $item->mahasiswa()->where('users.id', $mahasiswaId)->exists();
            return $item;
        });

        return KelasResource::collection($kelas);
    }

    /**
     * Register mahasiswa ke kelas tertentu
     */
    public function register(Request $request, $id)
    {
        $mahasiswaId = $request->user()->id;

        // Cek apakah kelas ada
        $kelas = Kelas::findOrFail($id);

        // Cek apakah mahasiswa sudah terdaftar
        if ($kelas->mahasiswa()->where('users.id', $mahasiswaId)->exists()) {
            return response()->json(['message' => 'Anda sudah terdaftar di kelas ini.'], 400);
        }

        // Daftarkan mahasiswa ke kelas
        KelasMahasiswa::create([
            'id_kelas' => $id,
            'id_mahasiswa' => $mahasiswaId,
        ]);

        return response()->json(['message' => 'Berhasil mendaftar ke kelas.'], 201);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
