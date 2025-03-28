<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{


    public function getProfile()
    {
        $user = Auth::user();
        $imagePath = $user->image === 'default.png'
            ? asset('assets/images/profile/default.png') // Path statis untuk default.png
            : Storage::url('images/users/' . $user->image); // Path storage untuk gambar yang diunggah

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'image' => $imagePath,
            'created_at' => $user->created_at->format('Y'),
            'status' => 'Aktif', // Bisa disesuaikan jika ada kolom status
            'points' => 1500, // Ganti dengan logika poin jika ada
        ]);
    }

    public function getKuisProgres()
    {
        $user = Auth::user();

        // Ambil semua kuis yang sudah diselesaikan oleh pengguna
        $kuisSelesai = $user->kuis()->whereNotNull('nilai')->get(); // Asumsi relasi kuis dengan nilai
        $totalKuisSelesai = $kuisSelesai->count();
        $rataRataNilai = $kuisSelesai->avg('nilai') ?? 0;

        // Ambil total semua kuis yang tersedia
        $totalKuis = Kuis::count(); // Asumsi model Kuis

        return response()->json([
            'total_kuis_selesai' => $totalKuisSelesai,
            'total_kuis' => $totalKuis, // Tambah field ini
            'rata_rata_nilai' => round($rataRataNilai, 2),
            'kuis_selesai' => $kuisSelesai->map(function ($kuis) {
                return [
                    'id' => $kuis->id,
                    'judul' => $kuis->judul,
                    'nama_kelas' => $kuis->kelas->nama, // Asumsi relasi dengan kelas
                    'nilai' => $kuis->nilai,
                    'nilai_lulus' => $kuis->nilai_lulus,
                ];
            }),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        // Update nama
        $user->name = $request->name;

        // Update foto jika ada
        if ($request->hasFile('image')) {
            // Hapus foto lama jika bukan default.png
            if ($user->image !== 'default.png') {
                Storage::delete('public/images/users/' . $user->image);
            }

            // Simpan foto baru
            $file = $request->file('image');
            $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images/users', $filename);
            $user->image = $filename;
        }

        $user->save();

        $imagePath = $user->image === 'default.png'
            ? asset('assets/images/profile/default.png')
            : Storage::url('images/users/' . $user->image);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'image' => $imagePath,
            ],
        ]);
    }
}
