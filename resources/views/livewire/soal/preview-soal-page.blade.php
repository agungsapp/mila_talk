<div class="container mt-5">
		<div class="card shadow-lg">

				<div class="card-body">
						<h3 class="mb-0">Preview Soal: {{ $soal->kuis->judul }}</h3>
						<small>Soal {{ $currentIndex + 1 }} dari {{ $allSoals->count() }}</small>

						@if ($soal->tipe == 'tebak_gambar')
								<div class="mb-4 text-center">
										<img src="{{ Storage::url($soal->konten['image_path']) }}" alt="Gambar" class="img-fluid"
												style="max-width: 400px;">
								</div>
								<ul class="list-unstyled">
										@foreach ($soal->konten['opsi'] as $key => $opsi)
												<li>{{ $key }}. {{ $opsi }}</li>
										@endforeach
								</ul>
								<p class="mt-3"><strong>Kunci Jawaban:</strong> {{ $soal->jawaban_benar }} -
										{{ $soal->konten['opsi'][$soal->jawaban_benar] }}</p>
						@elseif ($soal->tipe == 'mendengarkan')
								<div class="mb-4">
										<audio controls src="{{ Storage::url($soal->konten['audio_path']) }}" class="w-100"></audio>
								</div>
								<ul class="list-unstyled">
										@foreach ($soal->konten['opsi'] as $key => $opsi)
												<li>{{ $key }}. {{ $opsi }}</li>
										@endforeach
								</ul>
								<p class="mt-3"><strong>Kunci Jawaban:</strong> {{ $soal->jawaban_benar }} -
										{{ $soal->konten['opsi'][$soal->jawaban_benar] }}</p>
						@elseif ($soal->tipe == 'normal')
								<ul class="list-unstyled">
										@foreach ($soal->konten['opsi'] as $key => $opsi)
												<li>{{ $key }}. {{ $opsi }}</li>
										@endforeach
								</ul>
								<p class="mt-3"><strong>Kunci Jawaban:</strong> {{ $soal->jawaban_benar }} -
										{{ $soal->konten['opsi'][$soal->jawaban_benar] }}</p>
						@elseif ($soal->tipe == 'cocok_kata')
								<table class="table-bordered table">
										<thead>
												<tr>
														<th>Kiri</th>
														<th>Kanan</th>
												</tr>
										</thead>
										<tbody>
												@foreach ($soal->konten['pasangan'] as $pasang)
														<tr>
																<td>{{ $pasang['kiri'] }}</td>
																<td>{{ $pasang['kanan'] }}</td>
														</tr>
												@endforeach
										</tbody>
								</table>
								<p class="mt-3"><strong>Kunci Jawaban:</strong> Cocok seperti di atas</p>
						@endif

						<h4 class="mb-4">{{ $soal->konten['pertanyaan'] }}</h4>

				</div>
				<div class="card-footer">
						<div class="d-flex justify-content-between">
								<div>
										@if ($currentIndex > 0)
												<button wire:click="previous" class="btn btn-primary">Previous</button>
										@else
												<button class="btn btn-primary" disabled>Previous</button>
										@endif
										@if ($currentIndex < $allSoals->count() - 1)
												<button wire:click="next" class="btn btn-primary">Next</button>
										@else
												<button class="btn btn-primary" disabled>Next</button>
										@endif
								</div>
								<a href="{{ route('kuis-detail', $soal->kuis->id) }}" class="btn btn-secondary">Kembali ke Kuis</a>
						</div>
				</div>
		</div>
</div>
