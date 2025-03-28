<?php

namespace App\Livewire\Kelas;

use App\Models\Kelas;
use App\Traits\LivewireAlertHelpers;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class KelasPage extends Component
{

    use LivewireAlertHelpers;

    public string $tableName = 'kelas-table';


    public string $context = 'kelas';

    public $dataId;

    public $nama;

    public $isEdit = false;

    public $satuans;

    public $model;




    protected function rules()
    {
        return [
            'nama' => 'required|string|max:255',
        ];
    }
    protected function messages()
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
        ];
    }

    public function mount()
    {

        $this->model = new Kelas();
        // dd($this->model->all());
    }

    #[On('delete-item')]
    public function deleteItem($id)
    {
        $this->dataId = $id;
        $data = $this->model->findOrFail($id);
        $this->showCon("Apakah anda yakin ingin menghapus {$this->context} <strong>$data->nama</strong> ?", 'deleteItemConfirmed');
    }
    #[On('deleteItemConfirmed')]
    public function deleteItemConfirmed()
    {
        try {
            $this->model->destroy($this->dataId);
            $this->showSuccess("Berhasil menghapus data {$this->context} !");
            $this->dataId = null;
            $this->dispatch('pg:eventRefresh-' . $this->tableName);
        } catch (\Throwable $th) {
            // throw $th;
            $this->showError("Terjadi kesalahan pada server !");
        }
    }

    #[On('edit-item')]
    public function loadDataForEdit($id)
    {
        $data = $this->model->findOrFail($id);

        $this->isEdit = true;
        $this->dataId = $data->id;
        $this->nama = $data->nama;

        $this->alert('info', 'Silahkan edit pada form yang tersedia diatas.', [
            'showConfirmButton' => false,
            'timer' => 2000,
        ]);
    }

    public function test()
    {
        // dd($this->nama);
    }

    public function save()
    {
        // dd('lolos');
        try {
            $this->validate();

            if ($this->dataId) {
                // Update existing brand
                $data = $this->model->findOrFail($this->dataId);
                $data->update([
                    'nama' => $this->nama,
                    'id_dosen' => Auth::id()
                ]);
            } else {
                // Create new brand
                $this->model->create([
                    'nama' => $this->nama,
                    'id_dosen' => Auth::id()
                ]);
            }

            $this->showSuccess("Berhasil menyimpan data {$this->context}!");
            $this->dispatch('pg:eventRefresh-' . $this->tableName);
            $this->resetForm();
        } catch (\Throwable $th) {
            //throw $th;
            $this->showError('Terjadi kesalahan pada server !');
        }
    }

    public function batalEdit()
    {
        $this->resetForm();
    }


    private function resetForm()
    {
        $this->reset(['nama', 'dataId']);
        $this->isEdit = false;
    }
    public function render()
    {
        return view('livewire.kelas.kelas-page');
    }
}
