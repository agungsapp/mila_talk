<?php

namespace App\Livewire\Kelas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class KelasPage extends Component
{
    use WithPagination;

    public $nama; // Untuk input form
    public $kelasId; // Untuk edit
    public $isEdit = false; // Flag untuk mode edit

    protected $rules = [
        'nama' => 'required|string|max:255',
    ];

    public function render()
    {
        $kelas = Kelas::where('id_dosen', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(5); // Pagination 5 per halaman

        return view('livewire.kelas.kelas-page', [
            'kelas' => $kelas,
        ]);
    }

    public function simpan()
    {
        $this->validate();

        if ($this->isEdit) {
            // Update kelas
            Kelas::where('id', $this->kelasId)
                ->where('id_dosen', Auth::id())
                ->update(['nama' => $this->nama]);
            session()->flash('message', 'Kelas berhasil diperbarui!');
        } else {
            // Create kelas
            Kelas::create([
                'nama' => $this->nama,
                'id_dosen' => Auth::id(),
            ]);
            session()->flash('message', 'Kelas berhasil dibuat!');
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $kelas = Kelas::where('id', $id)
            ->where('id_dosen', Auth::id())
            ->firstOrFail();

        $this->kelasId = $kelas->id;
        $this->nama = $kelas->nama;
        $this->isEdit = true;
    }

    public function hapus($id)
    {
        Kelas::where('id', $id)
            ->where('id_dosen', Auth::id())
            ->delete();

        session()->flash('message', 'Kelas berhasil dihapus!');
    }

    public function batal()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->nama = '';
        $this->kelasId = null;
        $this->isEdit = false;
    }
}
