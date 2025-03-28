<?php

namespace App\Http\Controllers;

use App\Models\JawabanSiswa;
use App\Models\Kelas;
use App\Models\Kuis;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KuisController extends Controller
{

    // app/Http/Controllers/KuisController.php
    public function belumLulus()
    {
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login

        // Ambil semua jawaban siswa beserta relasi soal, kuis, dan kelas
        $jawabanSiswas = JawabanSiswa::where('user_id', $userId)
            ->with(['soal.kuis.kelas'])
            ->get();

        // Inisialisasi array untuk kuis yang belum lulus
        $kuisBelumLulus = [];
        $kuisIdsProcessed = []; // Untuk menghindari duplikat kuis

        // Proses setiap jawaban
        foreach ($jawabanSiswas as $jawaban) {
            // Pastikan relasi soal dan kuis ada
            if (!$jawaban->soal || !$jawaban->soal->kuis) {
                continue;
            }

            $kuis = $jawaban->soal->kuis;
            $kuisId = $kuis->id;

            // Hanya proses kuis yang belum diproses
            if (!isset($kuisIdsProcessed[$kuisId])) {
                // Ambil semua jawaban untuk kuis ini
                $jawabanKuis = JawabanSiswa::where('user_id', $userId)
                    ->whereHas('soal', function ($query) use ($kuisId) {
                        $query->where('id_kuis', $kuisId);
                    })
                    ->get();

                // Hitung nilai rata-rata untuk kuis ini
                $nilaiKuis = $jawabanKuis->avg('benar') * 100; // Skala 100, sesuaikan logika
                $nilaiLulus = $kuis->nilai_lulus;

                // Jika nilai < nilai_lulus, masukkan ke daftar kuis belum lulus
                if ($nilaiKuis < $nilaiLulus) {
                    $kuisBelumLulus[] = [
                        'id' => $kuis->id,
                        'judul' => $kuis->judul,
                        'nama_kelas' => $kuis->kelas->nama,
                        'nilai' => round($nilaiKuis, 2),
                        'nilai_lulus' => $nilaiLulus,
                    ];
                }

                $kuisIdsProcessed[$kuisId] = true;
            }
        }

        // Kembalikan response
        return response()->json($kuisBelumLulus);
    }

    // app/Http/Controllers/KuisController.php
    public function progres()
    {
        $userId = Auth::id(); // Ambil ID pengguna yang sedang login

        // Ambil semua jawaban siswa beserta relasi soal, kuis, dan kelas
        $jawabanSiswas = JawabanSiswa::where('user_id', $userId)
            ->with(['soal.kuis.kelas']) // Muat relasi berantai
            ->get();

        // Inisialisasi variabel
        $totalKuisSelesai = 0;
        $totalNilai = 0;
        $jumlahPercobaan = 0;
        $kuisSelesai = [];
        $kuisIdsProcessed = []; // Untuk menghindari duplikat kuis

        // Proses setiap jawaban
        foreach ($jawabanSiswas as $jawaban) {
            // Pastikan relasi soal dan kuis ada
            if (!$jawaban->soal || !$jawaban->soal->kuis) {
                continue;
            }

            $kuis = $jawaban->soal->kuis;
            $kuisId = $kuis->id;

            // Hitung nilai kuis berdasarkan semua jawaban untuk kuis ini
            if (!isset($kuisIdsProcessed[$kuisId])) {
                // Ambil semua jawaban untuk kuis ini
                $jawabanKuis = JawabanSiswa::where('user_id', $userId)
                    ->whereHas('soal', function ($query) use ($kuisId) {
                        $query->where('id_kuis', $kuisId);
                    })
                    ->get();

                // Hitung nilai rata-rata untuk kuis ini
                $nilaiKuis = $jawabanKuis->avg('benar') * 100; // Skala 100, sesuaikan logika
                $nilaiLulus = $kuis->nilai_lulus;

                // Tambah ke total nilai untuk rata-rata keseluruhan
                $totalNilai += $nilaiKuis;
                $jumlahPercobaan++;

                // Jika nilai >= nilai_lulus, masukkan ke daftar kuis selesai
                if ($nilaiKuis >= $nilaiLulus) {
                    $totalKuisSelesai++;
                    $kuisSelesai[] = [
                        'id' => $kuis->id,
                        'judul' => $kuis->judul,
                        'nama_kelas' => $kuis->kelas->nama,
                        'nilai' => round($nilaiKuis, 2),
                        'nilai_lulus' => $nilaiLulus,
                    ];
                }

                $kuisIdsProcessed[$kuisId] = true;
            }
        }

        // Hitung rata-rata nilai
        $rataRataNilai = $jumlahPercobaan > 0 ? round($totalNilai / $jumlahPercobaan, 2) : 0;

        // Kembalikan response
        return response()->json([
            'total_kuis_selesai' => $totalKuisSelesai,
            'rata_rata_nilai' => $rataRataNilai,
            'kuis_selesai' => array_values($kuisSelesai),
        ]);
    }

    public function riwayatPengerjaan($kuisId, Request $request)
    {
        $userId = Auth::id(); // Ambil ID user yang sedang login
        $kuis = Kuis::findOrFail($kuisId); // Ambil data kuis
        $nilaiLulus = $kuis->nilai_lulus;

        // Ambil semua jawaban siswa untuk kuis ini, kelompokkan berdasarkan percobaan
        $jawabanSiswas = JawabanSiswa::where('user_id', $userId)
            ->whereIn('soal_id', Soal::where('id_kuis', $kuisId)->pluck('id')) // Ubah kuis_id menjadi id_kuis
            ->get()
            ->groupBy(function ($item) {
                // Kelompokkan berdasarkan tanggal pengerjaan (tanpa detik untuk mengelompokkan percobaan yang sama)
                return $item->created_at->format('Y-m-d H:i');
            });

        $riwayat = [];
        $sudahLulus = false;

        foreach ($jawabanSiswas as $createdAt => $jawabanPercobaan) {
            $totalSoal = $jawabanPercobaan->count();
            $jawabanBenar = $jawabanPercobaan->where('benar', true)->count();
            $nilai = $totalSoal > 0 ? ($jawabanBenar / $totalSoal) * 100 : 0;
            $lulus = $nilai >= $nilaiLulus;

            if ($lulus) {
                $sudahLulus = true;
            }

            $riwayat[] = [
                'attempt_id' => $createdAt, // Gunakan timestamp sebagai ID unik
                'kuis_id' => $kuisId,
                'user_id' => $userId,
                'nilai' => round($nilai, 2),
                'lulus' => $lulus,
                'created_at' => $jawabanPercobaan->first()->created_at->toIso8601String(),
            ];
        }

        return response()->json([
            'data' => $riwayat,
            'sudah_lulus' => $sudahLulus,
        ]);
    }

    public function getSkor(string $id)
    {
        $kuis = Kuis::with(['soal'])->findOrFail($id);
        $user = auth()->user();

        $correctCount = 0;
        $totalSoal = $kuis->soal->count();

        foreach ($kuis->soal as $soal) {
            $jawabanSiswa = JawabanSiswa::where('user_id', $user->id)
                ->where('soal_id', $soal->id)
                ->first();

            if ($jawabanSiswa) {
                if ($soal->tipe === 'cocok_kata') {
                    $jawabanBenar = json_decode($soal->jawaban_benar, true);
                    $jawabanUser = json_decode($jawabanSiswa->jawaban, true);
                    $isCorrect = true;

                    for ($i = 0; $i < count($jawabanBenar); $i++) {
                        if (
                            !isset($jawabanUser[$i]) ||
                            $jawabanBenar[$i]['kiri'] !== $jawabanUser[$i]['kiri'] ||
                            $jawabanBenar[$i]['kanan'] !== $jawabanUser[$i]['kanan']
                        ) {
                            $isCorrect = false;
                            break;
                        }
                    }

                    if ($isCorrect) $correctCount++;
                } else {
                    if ($jawabanSiswa->jawaban === $soal->jawaban_benar) {
                        $correctCount++;
                    }
                }
            }
        }

        $skor = ($correctCount / $totalSoal) * 100;

        return response()->json([
            'skor' => $skor,
            'total_soal' => $totalSoal,
            'correct_count' => $correctCount,
        ]);
    }

    public function getSoalByNomor(string $id, int $nomor)
    {
        $kuis = Kuis::with(['kelas', 'soal'])->findOrFail($id);

        // Ambil soal berdasarkan nomor urut (nomor dimulai dari 1)
        $soal = $kuis->soal->sortBy('id')->values()->get($nomor - 1);

        if (!$soal) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }

        // Ubah image_path dan audio_path menjadi URL lengkap
        $baseUrl = config('app.url');
        $konten = $soal->konten;

        if ($soal->tipe === 'tebak_gambar' && isset($konten['image_path'])) {
            $relativePath = Storage::url($konten['image_path']);
            $konten['image_path'] = $baseUrl . $relativePath;
        }

        if ($soal->tipe === 'mendengarkan' && isset($konten['audio_path'])) {
            $relativePath = Storage::url($konten['audio_path']);
            $konten['audio_path'] = $baseUrl . $relativePath;
        }

        $soal->konten = $konten;

        return response()->json([
            'kuis' => $kuis,
            'soal' => $soal,
            'nomor' => $nomor,
            'total_soal' => $kuis->soal->count(),
        ]);
    }

    public function getKelasDetail($id, Request $request)
    {
        try {
            // Ambil kelas dengan relasi dosen dan kuis (termasuk soal di dalam kuis)
            $kelas = Kelas::with(['dosen', 'kuis.soal'])
                ->select('id', 'nama', 'deskripsi', 'id_dosen')
                ->findOrFail($id);

            $userId = auth()->id(); // Ambil ID user yang sedang login

            // Transform data ke array
            $kelasData = $kelas->toArray();

            // Transform kuis untuk hanya mengembalikan field yang dibutuhkan
            $kelasData['kuis'] = $kelas->kuis->map(function ($kuis) use ($userId) {
                // Hitung status lulus untuk kuis ini
                $nilaiLulus = $kuis->nilai_lulus;

                // Ambil semua jawaban siswa untuk kuis ini, kelompokkan berdasarkan percobaan
                $jawabanSiswas = JawabanSiswa::where('user_id', $userId)
                    ->whereIn('soal_id', Soal::where('id_kuis', $kuis->id)->pluck('id'))
                    ->get()
                    ->groupBy(function ($item) {
                        return $item->created_at->format('Y-m-d H:i');
                    });

                $sudahLulus = false;
                foreach ($jawabanSiswas as $jawabanPercobaan) {
                    $totalSoal = $jawabanPercobaan->count();
                    $jawabanBenar = $jawabanPercobaan->where('benar', true)->count();
                    $nilai = $totalSoal > 0 ? ($jawabanBenar / $totalSoal) * 100 : 0;
                    if ($nilai >= $nilaiLulus) {
                        $sudahLulus = true;
                        break;
                    }
                }

                return [
                    'id' => $kuis->id,
                    'judul' => $kuis->judul,
                    'deskripsi' => $kuis->deskripsi,
                    'nilai_lulus' => $kuis->nilai_lulus,
                    'jumlah_soal' => $kuis->soal->count(),
                    'sudah_lulus' => $sudahLulus, // Tambahkan status sudah_lulus
                ];
            })->toArray();

            return response()->json($kelasData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data kelas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Lihat kuis berdasarkan kelas
     */
    public function getKuisByKelas($id)
    {
        $kuis = Kuis::where('id_kelas', $id)->with('soal')->get();
        return response()->json($kuis);
    }

    /**
     * Display the specified resource.
     */
    private function fisherYatesShuffle(&$array)
    {
        // Inisialisasi ulang seed berdasarkan waktu saat ini
        mt_srand((int) (microtime(true) * 1000000));

        $count = count($array);
        for ($i = $count - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i); // Pilih indeks acak dari 0 hingga i
            // Tukar elemen pada indeks i dengan elemen pada indeks j
            $temp = $array[$i];
            $array[$i] = $array[$j];
            $array[$j] = $temp;
        }
    }

    public function show(string $id)
    {
        // Ambil data kuis beserta relasi
        $kuis = Kuis::with(['kelas', 'soal'])->findOrFail($id);

        // Ambil APP_URL dari konfigurasi
        $baseUrl = config('app.url'); // Misalnya http://localhost:8000

        // Loop melalui soal-soal untuk memproses image_path dan audio_path
        foreach ($kuis->soal as $soal) {
            $konten = $soal->konten;

            // Ubah image_path untuk tipe tebak_gambar
            if ($soal->tipe === 'tebak_gambar' && isset($konten['image_path'])) {
                $relativePath = Storage::url($konten['image_path']);
                $konten['image_path'] = $baseUrl . $relativePath;
            }

            // Ubah audio_path untuk tipe mendengarkan
            if ($soal->tipe === 'mendengarkan' && isset($konten['audio_path'])) {
                $relativePath = Storage::url($konten['audio_path']);
                $konten['audio_path'] = $baseUrl . $relativePath;
            }

            // Simpan kembali konten yang sudah diubah ke model
            $soal->konten = $konten;
        }

        // Konversi data kuis ke array untuk membangun response secara manual
        $kuisData = $kuis->toArray();

        // Ambil soal sebagai array dan acak menggunakan Fisher-Yates
        $soalArray = $kuis->soal->toArray();
        $this->fisherYatesShuffle($soalArray);

        // Gantikan bagian 'soal' pada response dengan array yang sudah diacak
        $kuisData['soal'] = $soalArray;

        // Kembalikan response JSON
        return response()->json($kuisData);
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
