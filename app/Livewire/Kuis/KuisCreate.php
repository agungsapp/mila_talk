<?php

namespace App\Livewire\Kuis;

use App\Models\Kelas;
use App\Models\Kuis;
use App\Traits\LivewireAlertHelpers;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class KuisCreate extends Component
{
    use LivewireAlertHelpers;

    public string $tableName = 'kuis-table';

    public $kelasId;
    public $judul, $deskripsi, $nilaiLulus;
    public $kelas;
    public $context = 'kuis';
    public $isEdit = false;
    public $dataId, $model;

    public function mount($id)
    {
        $this->kelasId = $id;
        $this->kelas = Kelas::with(['dosen', 'kuis'])->findOrFail($this->kelasId);
        $this->model = new Kuis();
    }

    #[On('delete-item')]
    public function deleteItem($id)
    {
        $this->dataId = $id;
        $data = $this->model->findOrFail($id);
        $this->showCon("Apakah anda yakin ingin menghapus {$this->context} <strong>{$data->judul}</strong> ?", 'deleteItemConfirmed');
    }

    #[On('deleteItemConfirmed')]
    public function deleteItemConfirmed()
    {
        try {
            $this->model->destroy($this->dataId);
            $this->showSuccess("Berhasil menghapus data {$this->context}!");
            $this->dataId = null;
            $this->dispatch('pg:eventRefresh-' . $this->tableName);
        } catch (\Throwable $th) {
            $this->showError("Terjadi kesalahan pada server!");
        }
    }

    #[On('edit-item')]
    public function loadDataForEdit($id)
    {
        $data = $this->model->findOrFail($id);

        $this->isEdit = true;
        $this->dataId = $data->id;
        $this->judul = $data->judul;
        $this->deskripsi = $data->deskripsi;
        $this->nilaiLulus = $data->nilai_lulus;

        $this->alert('info', 'Silahkan edit pada form yang tersedia di atas.', [
            'showConfirmButton' => false,
            'timer' => 2000,
        ]);
    }

    public function save()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nilaiLulus' => 'required|integer|min:1|max:100',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'nilaiLulus.required' => 'Nilai lulus wajib diisi.',
            'nilaiLulus.min' => 'Nilai lulus minimal 1.',
            'nilaiLulus.max' => 'Nilai lulus maksimal 100.',
        ]);

        if ($this->isEdit) {
            // Mode Update
            $kuis = Kuis::findOrFail($this->dataId);
            $kuis->update([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'nilai_lulus' => $this->nilaiLulus,
            ]);
            $this->showSuccess("Berhasil mengupdate data {$this->context}!");
        } else {
            // Mode Create
            Kuis::create([
                'id_kelas' => $this->kelasId,
                'id_dosen' => Auth::id(),
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'nilai_lulus' => $this->nilaiLulus,
            ]);
            $this->showSuccess("Berhasil menyimpan data {$this->context}!");
        }

        $this->resetForm();
        $this->dispatch('pg:eventRefresh-' . $this->tableName);
        session()->flash('message', $this->isEdit ? 'Kuis berhasil diupdate!' : 'Kuis berhasil disimpan!');
    }

    public function batalEdit()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->dataId = null;
        $this->alert('info', 'Edit dibatalkan.', [
            'showConfirmButton' => false,
            'timer' => 2000,
        ]);
    }

    public function updatedNilaiLulus($val)
    {
        if ($val < 1 || $val > 100) {
            $this->nilaiLulus = $val > 100 ? 100 : ($val < 1 ? 1 : $val);
        }
    }

    private function resetForm()
    {
        $this->reset(['judul', 'deskripsi', 'nilaiLulus']);
    }

    public function render()
    {
        return view('livewire.kuis.kuis-create');
    }
}
