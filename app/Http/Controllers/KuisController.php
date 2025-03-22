<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kuis;
use Illuminate\Http\Request;

class KuisController extends Controller
{

    public function getKelasDetail($id)
    {
        try {
            // Ambil kelas dengan relasi dosen dan kuis langsung
            $kelas = Kelas::with(['dosen', 'kuis'])
                ->select('id', 'nama', 'deskripsi', 'id_dosen')
                ->findOrFail($id);

            return response()->json($kelas, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data kelas'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
