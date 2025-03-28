<?php

namespace App\Livewire\Mahasiswa;

use App\Traits\LivewireAlertHelpers;
use Livewire\Component;

class KelasMahasiswaPage extends Component
{
    use LivewireAlertHelpers;

    public string $tableName = 'kelas-mahasiswa-table';


    public string $context = 'Kelas Mahasiswa';

    public $dataId;

    public $nama;

    public $isEdit = false;

    public $satuans;

    public $model;

    public function render()
    {
        return view('livewire.mahasiswa.kelas-mahasiswa-page');
    }
}
