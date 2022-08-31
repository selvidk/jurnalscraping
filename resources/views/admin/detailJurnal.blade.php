@extends('admin.template')
@section('title', 'Detail Jurnal')
@section('content')
<style>
	.column {
		float: left;
		width: 32%;
		margin: 1px;
		padding: 0 0px;
	}
	@media screen and (max-width: 1024px) {
		.column {
			width: 50%;
			display: block;
		}
	}
	@media screen and (max-width: 300px) {
		.column {
			width: 100%;
			display: block;
		}
	}
</style>
{{-- @foreach ($produk as $p) --}}
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-2">Detail Jurnal</h1>
		@if ($message = Session::get('sukses'))
			<div class="alert alert-success alert-dismissible" role="alert">
				<div class="alert-message">
					<strong>{{ $message }}</strong>
				</div>
			</div>
		@endif
		<div class="row">
			<div class="col-md-7">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">Data Umum Jurnal</h5>
					</div>
					<div class="card-body">
						<form action="/detail_jurnal/edit/{{ $data->id_jurnal }}" method="POST">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-12">
									<div class="mb-3">
										<div class="d-flex align-items-center d-flex justify-content-between mb-2">
											<label class="form-label mb-0" for="inputUsername">URL</label>
											<a href="{{ $data->url }}" class="badge badge-primary-light" target="_blank">Kunjungi Website</a>
										</div>
										<input type="url" class="form-control" id="url" name="url" placeholder="URL" value="{{ $data->url }}" required>
									</div>
									<div class="mb-3">
										<label class="form-label" for="inputUsername">Nama Jurnal</label>
										<textarea name="nama_jurnal" class="form-control" id="nama_jurnal" rows="2" required>{{ $data->nama_jurnal }}</textarea>
									</div>
									<div class="mb-3">
										<label class="form-label" for="inputUsername">Perguruan Tinggi</label>
										<select class="form-control" id="pt" name="pt" required>
											<option value="{{ $data->id_pt }}">{{ $data->nama_pt }}</option>
											<option disabled>Choose...</option>
										</select>
									</div>
									<div class="row">
										<div class="col-md-6 mb-3">
											<label class="form-label" for="inputUsername">Peringkat</label>
											<select class="form-control" id="peringkat" name="peringkat" required>
												<option value="{{ $data->peringkat }}">SINTA {{ $data->peringkat }}</option>
												<option disabled>Choose...</option>
												<option value="1">SINTA 1</option>
												<option value="2">SINTA 2</option>
												<option value="3">SINTA 3</option>
												<option value="4">SINTA 4</option>
												<option value="5">SINTA 5</option>
												<option value="6">SINTA 6</option>
											</select>
										</div>
										<div class="col-md-6 mb-3">
											<label class="form-label" for="inputUsername">Tanggal Pembaruan Data</label>
											<input type="text" class="form-control" id="tgl_ubah" name="tgl_ubah" placeholder="-" value="{{ $data->tgl_ubah }}" readonly>
										</div>
									</div>
								</div>
							</div>
							@if (Auth::user()->level == 1)
								<button type="submit" class="btn btn-primary float-right mt-1" name="simpan" id="simpan">Simpan Data</button>	
							@endif
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">Data Publikasi Jurnal</h5>
					</div>
					<div class="card-body">
						<form action="/detail_jurnal/tambah/jp/{{ $data->id_jurnal }}" method="post">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-12">
									<div class="input-group mb-3">
										<select class="form-control" id="bulan" name="bulan">
											<option selected disabled>Pilih Bulan</option>
											<option value="1">Januari</option>
											<option value="2">Februari</option>
											<option value="3">Maret</option>
											<option value="4">April</option>
											<option value="5">Mei</option>
											<option value="6">Juni</option>
											<option value="7">Juli</option>
											<option value="8">Agustus</option>
											<option value="9">September</option>
											<option value="10">Oktober</option>
											<option value="11">November</option>
											<option value="12">Desember</option>
										</select>
										<div class="input-group-append">
											<button class="btn btn-primary" type="submit">Tambahkan</button>
										</div>
									</div>
								</div>
							</div>
						</form>
						<div class="table-responsive">
							<table class="table table-striped table-sm">
								<thead>
									<tr>
										<th class="text-center">No.</th>
										<th>Jadwal</th>
										@if (Auth::user()->level == 1)
											<th colspan="2" class="text-center">Aksi</th>
										@else
											<th class="text-center">Aksi</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@php
										$jp = 1;
									@endphp
									@foreach ($j_publikasi as $j_p)
										@php
											switch($j_p->bulan){
												case 1: $bln = 'Januari'; break;
												case 2: $bln = 'Februari'; break;
												case 3: $bln = 'Maret'; break;
												case 4: $bln = 'April'; break;
												case 5: $bln = 'Mei'; break;
												case 6: $bln = 'Juni'; break;
												case 7: $bln = 'Juli'; break;
												case 8: $bln = 'Agustus'; break;
												case 9: $bln = 'September'; break;
												case 10: $bln = 'Oktober'; break;
												case 11: $bln = 'November'; break;
												case 12: $bln = 'Desember'; break;
												default: $bln = null;
											}
										@endphp
										@if ($bln != null)
    										<tr>
    											<td class="text-center">{{ $jp++ }}</td>
    											<form action="/detail_jurnal/edit/jp/{{ $j_p->id_publikasi_jurnal }}" method="post">
    												{{ csrf_field() }}
    												<td>
    													<select name="bulan" id="bulan" class="form-control form-control-sm">
    														<option value="{{ $j_p->bulan }}" selected>{{ $bln }}</option>
    														<option disabled>Pilih Bulan</option>
    														<option value="1">Januari</option>
    														<option value="2">Februari</option>
    														<option value="3">Maret</option>
    														<option value="4">April</option>
    														<option value="5">Mei</option>
    														<option value="6">Juni</option>
    														<option value="7">Juli</option>
    														<option value="8">Agustus</option>
    														<option value="9">September</option>
    														<option value="10">Oktober</option>
    														<option value="11">November</option>
    														<option value="12">Desember</option>
    													</select>
    												</td>
    												<td class="table-action text-center" style="width: 3px">
    													<a href="/detail_jurnal">
    														<button class="btn btn-sm btn-primary" style="border-radius: 5px"><i class="fas fa-check"></i></button>
    													</a>
    												</td>
    											</form>
    											@if (Auth::user()->level == 1)
    												<td class="table-action text-center" style="width: 3px">
    													<a type="button" data-toggle="modal" data-target="#hapus{{ $j_p->id_publikasi_jurnal }}">
    														<button class="btn btn-sm btn-danger" style="border-radius: 5px"><i class="fas fa-trash"></i></button>
    													</a>
    												</td>
    											@endif
    										</tr>
    										{{-- Modal Hapus --}}
    										<div class="modal fade" id="hapus{{ $j_p->id_publikasi_jurnal }}" tabindex="-1" role="dialog" aria-hidden="true">
    											<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    												<div class="modal-content">
    													<div class="modal-header">
    														<h5 class="modal-title"><strong>Hapus Data Publikasi Jurnal</strong></h5>
    														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    															<span aria-hidden="true">&times;</span>
    														</button>
    													</div>
    													<div class="modal-body m-3">
    														<p class="mb-0">Yakin ingin menghapus bulan <strong>{{ $bln }}</strong>?</p>
    													</div>
    													<div class="modal-footer">
    														<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    														<form action="/detail_jurnal/hapus/jp/{{ $j_p->id_publikasi_jurnal }}" method="post">
    															{{ csrf_field() }}
    															@method('delete')
    															<button type="submit" class="btn btn-primary">Hapus</button>
    														</form>
    													</div>
    												</div>
    											</div>
    										</div>
										@endif
									@endforeach
									
								</tbody>
									
								{{-- @endforeach --}}
							</table>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">Data Kategori Jurnal</h5>
					</div>
					<div class="card-body">
						<form action="/detail_jurnal/tambah/kat/{{ $data->id_jurnal }}" method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="row">
								<input type="hidden" name="produk" value="">
								<div class="col-md-12">
									<div class="input-group mb-3">
										<select class="form-control" id="kategori" name="kategori">
											<option selected disabled>Pilih Kategori</option>
											@foreach ($kategori as $k)
												<option value="{{ $k->nama_kategori }}">{{ $k->nama_kategori }}</option>
											@endforeach
										</select>
										<div class="input-group-append">
											<button class="btn btn-primary" type="submit">Tambahkan</button>
										</div>
									</div>
								</div>
							</div>
						</form>
						<div class="table-responsive">
							<table class="table table-striped table-sm">
								<thead>
									<tr>
										<th class="text-center">No.</th>
										<th>Kategori</th>
										@if (Auth::user()->level == 1)
											<th colspan="2" class="text-center">Aksi</th>
										@else
											<th class="text-center">Aksi</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@php
										$jk = 1;
									@endphp
									@foreach ($j_kategori as $j_k)
										<tr>
											<td class="text-center">{{ $jk++ }}</td>
											<form action="/detail_jurnal/edit/kat/{{ $j_k->id_kategori }}" method="post">
												{{ csrf_field() }}
												<td>
													<select class="form-control" id="kategori" name="kategori">
														<option value="{{ $j_k->id_kategori }}" selected>{{ $j_k->nama_kategori }}</option>
														<option disabled>Pilih Kategori</option>
														@foreach ($kategori as $k)
															<option value="{{ $k->nama_kategori }}">{{ $k->nama_kategori }}</option>
														@endforeach
													</select>
												</td>
												<td class="table-action text-center" style="width: 3px">
													{{-- <a href="/detail_jurnal"> --}}
													<button type="submit" class="btn btn-sm btn-primary" style="border-radius: 5px"><i class="fas fa-check"></i></button>
													{{-- </a> --}}
												</td>
											</form>
											@if (Auth::user()->level == 1)
												<td class="table-action text-center" style="width: 3px">
													<a type="button" data-toggle="modal" data-target="#hapusK{{ $j_k->id_kategori }}">
														<button class="btn btn-sm btn-danger" style="border-radius: 5px"><i class="fas fa-trash"></i></button>
													</a>
												</td>
											@endif
										</tr>
										{{-- Modal Hapus --}}
										<div class="modal fade" id="hapusK{{ $j_k->id_kategori }}" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title"><strong>Hapus Data Kategori Jurnal</strong></h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-3">
														<p class="mb-0">Yakin ingin menghapus kategori <strong>{{ $j_k->nama_kategori }}</strong>?</p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
														<form action="/detail_jurnal/hapus/kat/{{ $j_k->id_kategori }}" method="post">
															{{ csrf_field() }}
															@method('delete')
															<button type="submit" class="btn btn-primary">Hapus</button>
														</form>
													</div>
												</div>
											</div>
										</div>
									@endforeach
								</tbody>
								
								{{-- @endforeach --}}
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
{{-- @endforeach --}}
@endsection