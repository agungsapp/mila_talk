<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Membuat 5 dosen
        User::factory()
            ->count(5)
            ->dosen() // Menggunakan state dosen dari UserFactory
            ->create();

        // Membuat 1 mahasiswa dengan data spesifik
        User::create([
            'name' => 'Budi Santoso', // Nama sesuai kebutuhan
            'email' => 'budi@example.com',
            'password' => bcrypt('password123'), // Hash password
            'role' => 'mahasiswa',
        ]);
    }
}
