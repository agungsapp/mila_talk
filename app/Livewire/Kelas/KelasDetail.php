<?php

namespace App\Livewire\Kelas;

use App\Models\Kelas;
use Livewire\Component;

class KelasDetail extends Component
{

    public $kelasId;
    public $kelas;

    public function mount($id)
    {
        $this->kelasId = $id;
        $this->kelas = Kelas::with(['dosen', 'kuis'])->find($this->kelasId);
        // dd($id);
    }
    public function render()
    {
        return view('livewire.kelas.kelas-detail');
    }
}
