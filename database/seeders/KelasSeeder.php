<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $dosenIds = User::where('role', 'dosen')->pluck('id');

        foreach (range(1, 10) as $i) {
            Kelas::create([
                'nama' => 'Kelas Bahasa Inggris ' . $i,
                'id_dosen' => $dosenIds->random(),
                'deskripsi' => 'Kelas Bahasa Inggris untuk mahasiswa tingkat ' . $i,
            ]);
        }
    }
}
