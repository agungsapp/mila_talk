<?php

namespace App\Livewire\Kuis;

use App\Models\Kuis;
use App\Traits\LivewireAlertHelpers;
use Livewire\Component;
use Livewire\WithFileUploads;

class KuisDetail extends Component
{
    use LivewireAlertHelpers, WithFileUploads;

    public $kelasId, $kuisId;
    public $kelas, $kuis, $soals;
    public $context = 'soal';
    public $isEdit = false;
    public $dataId;

    // Form properties
    public $tipe, $pertanyaan, $jawabanBenar;
    public $imageFile, $audioFile;
    public $opsiA, $opsiB, $opsiC, $opsiD, $opsiE;
    public $pasanganKiri = [], $pasanganKanan = [];

    public function mount($id)
    {
        $this->kuisId = $id;
        $kuis = Kuis::with(['kelas', 'soal'])->findOrFail($id);
        $this->kelas = $kuis->kelas;
        $this->kuis = $kuis;
        $this->soals = $kuis->soal;
        $this->kelasId = $this->kelas->id;
    }

    public function saveSoal()
    {
        $rules = [
            'tipe' => 'required|in:tebak_gambar,cocok_kata,mendengarkan,normal',
            'pertanyaan' => 'required|string|max:255',
        ];

        if (in_array($this->tipe, ['tebak_gambar', 'mendengarkan', 'normal'])) {
            $rules = array_merge($rules, [
                'opsiA' => 'required|string|max:255',
                'opsiB' => 'required|string|max:255',
                'opsiC' => 'required|string|max:255',
                'opsiD' => 'required|string|max:255',
                'opsiE' => 'required|string|max:255',
                'jawabanBenar' => 'required|in:A,B,C,D,E',
            ]);
        }

        if ($this->tipe === 'tebak_gambar') {
            $rules['imageFile'] = $this->isEdit ? 'nullable|image|max:2048' : 'required|image|max:2048';
        } elseif ($this->tipe === 'mendengarkan') {
            // Perluas MIME type untuk MP3 dan WAV
            $rules['audioFile'] = $this->isEdit
                ? 'nullable|file|mimes:mp3,wav,mpeg,audio/mpeg,audio/wav|max:2048'
                : 'required|file|mimes:mp3,wav,mpeg,audio/mpeg,audio/wav|max:2048';
        } elseif ($this->tipe === 'cocok_kata') {
            $rules['pasanganKiri.*'] = 'required|string|max:255';
            $rules['pasanganKanan.*'] = 'required|string|max:255';
        }

        $this->validate($rules);

        $konten = ['pertanyaan' => $this->pertanyaan];

        if ($this->tipe === 'tebak_gambar') {
            if ($this->imageFile) {
                $konten['image_path'] = $this->imageFile->store('soal', 'public');
            } elseif ($this->isEdit) {
                $konten['image_path'] = $this->soals->find($this->dataId)->konten['image_path'];
            }
            $konten['opsi'] = [
                'A' => $this->opsiA,
                'B' => $this->opsiB,
                'C' => $this->opsiC,
                'D' => $this->opsiD,
                'E' => $this->opsiE
            ];
        } elseif ($this->tipe === 'mendengarkan') {
            if ($this->audioFile) {
                $konten['audio_path'] = $this->audioFile->store('soal', 'public');
            } elseif ($this->isEdit) {
                $konten['audio_path'] = $this->soals->find($this->dataId)->konten['audio_path'];
            }
            $konten['opsi'] = [
                'A' => $this->opsiA,
                'B' => $this->opsiB,
                'C' => $this->opsiC,
                'D' => $this->opsiD,
                'E' => $this->opsiE
            ];
        } elseif ($this->tipe === 'normal') {
            $konten['opsi'] = [
                'A' => $this->opsiA,
                'B' => $this->opsiB,
                'C' => $this->opsiC,
                'D' => $this->opsiD,
                'E' => $this->opsiE
            ];
        } elseif ($this->tipe === 'cocok_kata') {
            $pasangan = [];
            foreach ($this->pasanganKiri as $index => $kiri) {
                if (!empty($kiri) && !empty($this->pasanganKanan[$index])) {
                    $pasangan[] = ['kiri' => $kiri, 'kanan' => $this->pasanganKanan[$index]];
                }
            }
            $konten['pasangan'] = $pasangan;
            $this->jawabanBenar = json_encode($pasangan);
        }

        if ($this->isEdit) {
            $soal = $this->soals->find($this->dataId);
            $soal->update([
                'tipe' => $this->tipe,
                'konten' => $konten,
                'jawaban_benar' => $this->jawabanBenar,
            ]);
            $this->showSuccess("Berhasil mengupdate {$this->context}!");
        } else {
            $this->soals->push(
                Kuis::find($this->kuisId)->soal()->create([
                    'id_kuis' => $this->kuisId,
                    'tipe' => $this->tipe,
                    'konten' => $konten,
                    'jawaban_benar' => $this->jawabanBenar,
                ])
            );
            $this->showSuccess("Berhasil menyimpan {$this->context}!");
        }

        $this->resetForm();
    }

    public function editSoal($id)
    {
        $soal = $this->soals->find($id);
        $this->isEdit = true;
        $this->dataId = $soal->id;
        $this->tipe = $soal->tipe;
        $this->pertanyaan = $soal->konten['pertanyaan'];
        $this->jawabanBenar = $soal->jawaban_benar;

        if (in_array($soal->tipe, ['tebak_gambar', 'mendengarkan', 'normal'])) {
            $this->opsiA = $soal->konten['opsi']['A'];
            $this->opsiB = $soal->konten['opsi']['B'];
            $this->opsiC = $soal->konten['opsi']['C'];
            $this->opsiD = $soal->konten['opsi']['D'];
            $this->opsiE = $soal->konten['opsi']['E'];
        } elseif ($soal->tipe === 'cocok_kata') {
            $this->pasanganKiri = array_column($soal->konten['pasangan'], 'kiri');
            $this->pasanganKanan = array_column($soal->konten['pasangan'], 'kanan');
        }
    }

    public function deleteSoal($id)
    {
        $soal = $this->soals->find($id);
        $soal->delete();
        $this->soals = $this->soals->where('id', '!=', $id);
        $this->showSuccess("Berhasil menghapus {$this->context}!");
    }

    public function resetForm()
    {
        $this->reset(['tipe', 'pertanyaan', 'jawabanBenar', 'imageFile', 'audioFile', 'opsiA', 'opsiB', 'opsiC', 'opsiD', 'opsiE', 'pasanganKiri', 'pasanganKanan', 'isEdit', 'dataId']);
    }



    public function render()
    {
        return view('livewire.kuis.kuis-detail');
    }
}
