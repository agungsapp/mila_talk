<?php

namespace App\Livewire\Soal;

use App\Models\Soal;
use Livewire\Component;

class PreviewSoalPage extends Component
{
    public $soal;
    public $allSoals;
    public $currentIndex;

    public function mount($id)
    {
        // Ambil soal spesifik
        $this->soal = Soal::with('kuis')->findOrFail($id);

        // Ambil semua soal dari kuis yang sama
        $this->allSoals = Soal::where('id_kuis', $this->soal->kuis->id)->get();

        // Tentukan indeks soal saat ini
        $this->currentIndex = $this->allSoals->search(function ($item) use ($id) {
            return $item->id == $id;
        });
    }

    public function previous()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->soal = $this->allSoals[$this->currentIndex];
        }
    }

    public function next()
    {
        if ($this->currentIndex < $this->allSoals->count() - 1) {
            $this->currentIndex++;
            $this->soal = $this->allSoals[$this->currentIndex];
        }
    }

    public function render()
    {
        return view('livewire.soal.preview-soal-page');
    }
}
