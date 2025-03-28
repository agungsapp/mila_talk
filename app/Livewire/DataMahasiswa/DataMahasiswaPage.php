<?php

namespace App\Livewire\DataMahasiswa;

use App\Traits\LivewireAlertHelpers;
use Livewire\Component;

class DataMahasiswaPage extends Component
{
    use LivewireAlertHelpers;

    public string $tableName = 'data-mahasiswa-table';


    public string $context = 'Data Mahasiswa';

    public $dataId;

    public $nama;

    public $isEdit = false;

    public $satuans;

    public $model;

    public function render()
    {
        return view('livewire.data-mahasiswa.data-mahasiswa-page');
    }
}
