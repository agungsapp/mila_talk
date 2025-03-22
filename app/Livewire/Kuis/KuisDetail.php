<?php

namespace App\Livewire\Kuis;

use App\Models\Kuis;
use App\Traits\LivewireAlertHelpers;
use Livewire\Component;

class KuisDetail extends Component
{
    use LivewireAlertHelpers;

    public string $tableName = 'kuis-table';

    public $kelasId;
    public $judul, $deskripsi, $nilaiLulus;
    public $kelas, $kuis;
    public $context = 'kuis';
    public $isEdit = false;
    public $dataId, $model;

    public function mount($id)
    {
        $kuis = Kuis::with(['soal', 'kelas'])->find($id);
        $this->kelas = $kuis->kelas;
        $this->kuis = $kuis->soal;
    }

    public function render()
    {
        return view('livewire.kuis.kuis-detail');
    }
}

// terakhir di sini pembuatan crud soal belum selesai . 
