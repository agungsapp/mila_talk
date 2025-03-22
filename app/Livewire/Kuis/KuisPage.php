<?php

namespace App\Livewire\Kuis;

use Livewire\Component;

class KuisPage extends Component
{

    public $context = 'kelas';
    public $isEdit = false;

    public function save()
    {
        dd('oke test');
    }
    public function render()
    {
        return view('livewire.kuis.kuis-page');
    }
}
