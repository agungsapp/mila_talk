<div>
		<!-- Header -->
		<div class="row mb-4">
				<div class="col-12">
						<h1 class="text-capitalize fw-bold" style="color: #2c3e50;">
								<i class="fas fa-chalkboard-teacher me-2"></i> Detail Kelas: {{ $kelas->nama }}
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


		@livewire('kuis.kuis-create', ['id' => $kelas->id])


		@if ($kelas->kuis->count() == 0)
				<div class="row mt-4">
						<div class="col-12">
								<div class="card">
										<div class="card-body">
												<h5 class="text-center">-- data kuis belum ada untuk kelas ini --</h5>
										</div>
								</div>
						</div>
				</div>
		@endif
</div>
