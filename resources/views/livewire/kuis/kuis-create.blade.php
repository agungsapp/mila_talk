<div class="row my-4">
		<div class="col-12">
				<div class="card border-0 shadow-lg">
						<form wire:submit.prevent="save">
								<div class="card-body">
										<div class="row">
												<div class="col-6 col-lg-3 mb-3">
														<label for="judul" class="form-label text-capitalize wajib">Judul</label>
														<input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul"
																wire:model="judul" placeholder="Judul {{ $context }}...">
														@error('judul')
																<div class="invalid-feedback">{{ $message }}</div>
														@enderror
												</div>

												<div class="col-6 col-lg-3 mb-3">
														<label for="nilaiLulus" class="form-label text-capitalize wajib">Nilai Lulus</label>
														<input type="number" min="1" max="100"
																class="form-control @error('nilaiLulus') is-invalid @enderror" id="nilaiLulus"
																wire:model.live.debounce.500ms="nilaiLulus" placeholder="Nilai lulus {{ $context }}...">
														@error('nilaiLulus')
																<div class="invalid-feedback">{{ $message }}</div>
														@enderror
												</div>

												<div class="col-12 mb-3">
														<label for="deskripsi" class="form-label text-capitalize wajib">Deskripsi</label>
														<textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3"
														  wire:model="deskripsi"></textarea>
														@error('deskripsi')
																<div class="invalid-feedback">{{ $message }}</div>
														@enderror
												</div>
										</div>
								</div>
								<div class="card-footer">
										<button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Simpan' }}</button>
										@if ($isEdit)
												<button type="button" wire:click='batalEdit' class="btn btn-danger">Batal</button>
										@endif
								</div>
						</form>
				</div>
		</div>

		<div class="col-12 my-4">
				<div class="card">
						<div class="card-body">
								<livewire:kuis.kuis-table :kelas-id="$kelasId" />
						</div>
				</div>
		</div>
</div>

@if (session('message'))
		<div class="alert alert-success mt-3">
				{{ session('message') }}
		</div>
@endif
