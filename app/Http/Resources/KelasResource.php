<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KelasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'dosen' => [
                'id' => $this->dosen->id,
                'nama' => $this->dosen->name,
            ],
            // 'mahasiswa' => $this->mahasiswa->map(function ($mhs) {
            //     return [
            //         'id' => $mhs->id,
            //         'nama' => $mhs->name,
            //     ];
            // }),
            // 'jumlah_mahasiswa' => $this->mahasiswa->count(),
            // 'kuis' => $this->kuis->map(function ($kuis) {
            //     return [
            //         'id' => $kuis->id,
            //         'judul' => $kuis->judul,
            //         'deskripsi' => $kuis->deskripsi,
            //     ];
            // }),
        ];
    }
}
