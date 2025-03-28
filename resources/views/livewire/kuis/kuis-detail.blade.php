<div>
		<!-- Header dan Detail Kuis sama seperti sebelumnya -->

		<div class="row mb-4">
				<div class="col-12">
						<h1 class="text-capitalize fw-bold" style="color: #2c3e50;">
								<i class="fas fa-chalkboard-teacher me-2"></i> Detail kuis: {{ $kelas->nama }}
						</h1>
				</div>
		</div>

		<!-- Card Detail Kelas -->
		<div class="row">
				<div class="col-12"> <!-- Batasi lebar card di layar besar -->
						<div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
								<!-- Header dengan Gradien -->
								<div class="card-header py-4">
										<h5 class="card-title fw-bold m-0">
												<i class="fas fa-info-circle me-2"></i> Informasi Kelas
										</h5>
								</div>
								<!-- Body dengan Ikon dan Styling -->
								<div class="card-body bg-light p-4">
										<div class="row g-3">
												<div class="col-12 col-md-6">
														<div class="d-flex align-items-center">
																<i class="fas fa-book fa-2x text-primary me-3"></i>
																<div>
																		<strong>Nama Kelas</strong><br>
																		<span>{{ $kelas->nama }}</span>
																</div>
														</div>
												</div>
												<div class="col-12 col-md-6">
														<div class="d-flex align-items-center">
																<i class="fas fa-user-tie fa-2x text-primary me-3"></i>
																<div>
																		<strong>Dosen</strong><br>
																		<span>{{ $kelas->dosen->name }}</span>
																</div>
														</div>
												</div>
												<div class="col-12">
														<div class="d-flex align-items-center">
																<i class="fas fa-file-alt fa-2x text-primary me-3"></i>
																<div>
																		<strong>Deskripsi</strong><br>
																		<span>{{ $kelas->deskripsi ?? 'Tidak ada deskripsi' }}</span>
																</div>
														</div>
												</div>
												<div class="col-12 col-md-6">
														<div class="d-flex align-items-center">
																<i class="fas fa-calendar-alt fa-2x text-primary me-3"></i>
																<div>
																		<strong>Dibuat Pada</strong><br>
																		<span>{{ $kelas->created_at->format('d M Y H:i') }}</span>
																</div>
														</div>
												</div>
										</div>
								</div>
								<!-- Footer dengan Tombol -->
								<div class="card-footer d-flex justify-content-between bg-white p-3">
										<a href="{{ route('kelas') }}" class="btn btn-outline-secondary btn-sm">
												<i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Kelas
										</a>
										{{-- <a href="{{ route('kuis-create', $kelas->id) }}" class="btn btn-primary btn-sm">
												<i class="fas fa-plus me-2"></i> Buat Kuis
										</a> --}}
								</div>
						</div>
				</div>
		</div>


		<!-- Form CRUD Soal -->
		<div class="row mt-4">
				<div class="col-12">
						<div class="card border-0 shadow-lg">
								<div class="card-header py-4">
										<h5 class="card-title fw-bold m-0">
												<i class="fas fa-question-circle me-2"></i> Tambah/Edit Soal
										</h5>
								</div>
								<form wire:submit.prevent="saveSoal">
										<div class="card-body">
												<div class="row">
														<div class="col-md-4 mb-3">
																<label for="tipe" class="form-label">Tipe Soal</label>
																<select wire:model.live="tipe" class="form-control @error('tipe') is-invalid @enderror" id="tipe">
																		<option value="">Pilih Tipe</option>
																		<option value="tebak_gambar">Tebak Gambar</option>
																		<option value="cocok_kata">Cocok Kata</option>
																		<option value="mendengarkan">Mendengarkan</option>
																		<option value="normal">Normal</option>
																</select>
																@error('tipe')
																		<div class="invalid-feedback">{{ $message }}</div>
																@enderror
														</div>

														<div class="col-md-8 mb-3">
																<label for="pertanyaan" class="form-label">Pertanyaan</label>
																<input type="text" wire:model="pertanyaan"
																		class="form-control @error('pertanyaan') is-invalid @enderror" id="pertanyaan">
																@error('pertanyaan')
																		<div class="invalid-feedback">{{ $message }}</div>
																@enderror
														</div>

														@if ($tipe == 'tebak_gambar')
																<div class="col-md-4 mb-3">
																		<label for="imageFile" class="form-label">Upload Gambar</label>
																		<input type="file" wire:model="imageFile"
																				class="form-control @error('imageFile') is-invalid @enderror" id="imageFile">
																		@error('imageFile')
																				<div class="invalid-feedback">{{ $message }}</div>
																		@enderror
																</div>
																@if ($isEdit && $dataId)
																		<div class="col-md-4 mb-3">
																				<label class="form-label">Preview Gambar</label>
																				<img src="{{ Storage::url($soals->find($dataId)->konten['image_path']) }}" alt="Preview"
																						style="max-width: 400px;">
																		</div>
																@endif
														@elseif ($tipe == 'mendengarkan')
																<div class="col-md-4 mb-3">
																		<label for="audioFile" class="form-label">Upload Audio</label>
																		<input type="file" wire:model="audioFile"
																				class="form-control @error('audioFile') is-invalid @enderror" id="audioFile">
																		@error('audioFile')
																				<div class="invalid-feedback">{{ $message }}</div>
																		@enderror
																</div>
																@if ($isEdit && $dataId)
																		<div class="col-md-4 mb-3">
																				<label class="form-label">Preview Audio</label>
																				<audio controls src="{{ Storage::url($soals->find($dataId)->konten['audio_path']) }}"></audio>
																		</div>
																@endif
														@elseif ($tipe == 'cocok_kata')
																<div class="col-12 mb-3">
																		<label class="form-label">Pasangan (Tambah 2 untuk contoh)</label>
																		<div class="row">
																				<div class="col-md-6">
																						<input type="text" wire:model="pasanganKiri.0" class="form-control mb-2" placeholder="Kiri 1">
																						<input type="text" wire:model="pasanganKiri.1" class="form-control" placeholder="Kiri 2">
																				</div>
																				<div class="col-md-6">
																						<input type="text" wire:model="pasanganKanan.0" class="form-control mb-2"
																								placeholder="Kanan 1">
																						<input type="text" wire:model="pasanganKanan.1" class="form-control" placeholder="Kanan 2">
																				</div>
																		</div>
																		@error('pasanganKiri.*')
																				<div class="text-danger">{{ $message }}</div>
																		@enderror
																		@error('pasanganKanan.*')
																				<div class="text-danger">{{ $message }}</div>
																		@enderror
																</div>
														@endif

														@if (in_array($tipe, ['tebak_gambar', 'mendengarkan', 'normal']))
																<div class="col-12 mb-3">
																		<label class="form-label">Opsi Jawaban</label>
																		<input type="text" wire:model="opsiA"
																				class="form-control @error('opsiA') is-invalid @enderror mb-2" placeholder="A">
																		<input type="text" wire:model="opsiB"
																				class="form-control @error('opsiB') is-invalid @enderror mb-2" placeholder="B">
																		<input type="text" wire:model="opsiC"
																				class="form-control @error('opsiC') is-invalid @enderror mb-2" placeholder="C">
																		<input type="text" wire:model="opsiD"
																				class="form-control @error('opsiD') is-invalid @enderror mb-2" placeholder="D">
																		<input type="text" wire:model="opsiE" class="form-control @error('opsiE') is-invalid @enderror"
																				placeholder="E">
																</div>
																<div class="col-md-4 mb-3">
																		<label for="jawabanBenar" class="form-label">Jawaban Benar</label>
																		<select wire:model="jawabanBenar" class="form-control @error('jawabanBenar') is-invalid @enderror"
																				id="jawabanBenar">
																				<option value="">Pilih</option>
																				<option value="A">A</option>
																				<option value="B">B</option>
																				<option value="C">C</option>
																				<option value="D">D</option>
																				<option value="E">E</option>
																		</select>
																		@error('jawabanBenar')
																				<div class="invalid-feedback">{{ $message }}</div>
																		@enderror
																</div>
														@endif
												</div>
										</div>
										<div class="card-footer">
												<button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Simpan' }}</button>
												@if ($isEdit)
														<button type="button" wire:click="resetForm" class="btn btn-danger">Batal</button>
												@endif
										</div>
								</form>
						</div>
				</div>
		</div>

		<!-- Tabel Soal -->
		<div class="row mt-4">
				<div class="col-12">
						<div class="card">
								<div class="card-body">
										@if ($soals->count() == 0)
												<h5 class="text-center">-- Belum ada soal untuk kuis ini --</h5>
										@else
												<table class="table-striped table">
														<thead>
																<tr>
																		<th>Tipe</th>
																		<th>Pertanyaan</th>
																		<th>Konten</th>
																		<th>Jawaban Benar</th>
																		<th>Aksi</th>
																</tr>
														</thead>
														<tbody>
																@foreach ($soals as $soal)
																		<tr>
																				<td>{{ ucfirst(str_replace('_', ' ', $soal->tipe)) }}</td>
																				<td>{{ $soal->konten['pertanyaan'] }}</td>
																				<td>
																						@if ($soal->tipe == 'tebak_gambar')
																								<img src="{{ Storage::url($soal->konten['image_path']) }}" alt="Gambar"
																										style="max-width: 100px;">
																						@elseif ($soal->tipe == 'mendengarkan')
																								<audio controls src="{{ Storage::url($soal->konten['audio_path']) }}"></audio>
																						@elseif ($soal->tipe == 'cocok_kata')
																								@foreach ($soal->konten['pasangan'] as $pasang)
																										{{ $pasang['kiri'] }} - {{ $pasang['kanan'] }}<br>
																								@endforeach
																						@endif
																				</td>
																				<td>{{ $soal->jawaban_benar }}</td>
																				<td>
																						<a href="{{ route('preview-soal', $soal->id) }}" class="btn btn-info btn-sm">Preview</a>
																						<button wire:click="editSoal({{ $soal->id }})" class="btn btn-warning btn-sm">Edit</button>
																						<button wire:click="deleteSoal({{ $soal->id }})"
																								class="btn btn-danger btn-sm">Hapus</button>
																				</td>
																		</tr>
																@endforeach
														</tbody>
												</table>
										@endif
								</div>
						</div>
				</div>
		</div>

</div>
