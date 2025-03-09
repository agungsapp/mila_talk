<div>
		<!-- Pesan Sukses -->
		@if (session()->has('message'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
						{{ session('message') }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
						</button>
				</div>
		@endif

		<!-- Form Create/Edit -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">
												{{ $isEdit ? 'Edit Kelas' : 'Buat Kelas Baru' }}
										</h6>
								</div>
								<div class="card-body">
										<form wire:submit.prevent="simpan">
												<div class="form-group">
														<label for="nama">Nama Kelas</label>
														<input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
																wire:model="nama" placeholder="Masukkan nama kelas">
														@error('nama')
																<span class="invalid-feedback">{{ $message }}</span>
														@enderror
												</div>
												<button type="submit" class="btn btn-primary">
														{{ $isEdit ? 'Simpan Perubahan' : 'Buat Kelas' }}
												</button>
												@if ($isEdit)
														<button type="button" class="btn btn-secondary" wire:click="batal">Batal</button>
												@endif
										</form>
								</div>
						</div>
				</div>
		</div>

		<!-- Tabel Kelas -->
		<div class="row">
				<div class="col-12">
						<div class="card shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">Daftar Kelas</h6>
								</div>
								<div class="card-body">
										@if ($kelas->isEmpty())
												<p class="text-muted">Belum ada kelas yang dibuat.</p>
										@else
												<div class="table-responsive">
														<table class="table-bordered table-hover table">
																<thead class="thead-light">
																		<tr>
																				<th>No</th>
																				<th>Nama Kelas</th>
																				<th>Dibuat Pada</th>
																				<th>Aksi</th>
																		</tr>
																</thead>
																<tbody>
																		@foreach ($kelas as $index => $k)
																				<tr>
																						<td>{{ $kelas->firstItem() + $index }}</td>
																						<td>{{ $k->nama }}</td>
																						<td>{{ $k->created_at->format('d M Y H:i') }}</td>
																						<td>
																								<button class="btn btn-sm btn-warning" wire:click="edit({{ $k->id }})">Edit</button>
																								<button class="btn btn-sm btn-danger" wire:click="hapus({{ $k->id }})"
																										onclick="return confirm('Yakin hapus kelas ini?')">Hapus</button>
																						</td>
																				</tr>
																		@endforeach
																</tbody>
														</table>
												</div>
												<!-- Pagination -->
												<div class="mt-3">
														{{ $kelas->links() }}
												</div>
										@endif
								</div>
						</div>
				</div>
		</div>
</div>
