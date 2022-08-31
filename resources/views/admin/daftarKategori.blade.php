@extends('admin.template')
@section('title', 'Daftar Kategori')
@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-2">Data Kategori</h1>
		@if ($message = Session::get('sukses'))
			<div class="alert alert-success alert-dismissible" role="alert">
				<div class="alert-message">
					<strong>{{ $message }}</strong>
				</div>
			</div>
		@endif
		@if ($message = Session::get('gagal'))
			<div class="alert alert-danger alert-dismissible" role="alert">
				<div class="alert-message">
					<strong>{{ $message }}</strong>
				</div>
			</div>
		@endif
		<div class="row">
			<div class="col-md-12">
				<div class="tab-content">
					<div class="tab-pane fade show active" id="produk" role="tabpanel">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-sm-12">
										<label><h5 class="card-title">Data Kategori
												@if (Auth::user()->level == 1)
													<a type="button" data-toggle="modal" data-target="#tambah"><button class="btn btn-pill btn-primary btn-sm ml-2"><i class="fas fa-plus"></i></i></button></a>
												@endif
											</h5>
										</label>
									</div>
									{{-- Modal Tambah --}}
									<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title"><strong>Tambah Kategori</strong></h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form action="/daftar_kategori/tambah" method="post">
													{{ csrf_field() }}
													<div class="modal-body m-2">
														<div class="mb-3">
															<label class="form-label" for="inputUsername">Nama Kategori</label>
															<input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Nama Kategori" required>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
														<button type="submit" class="btn btn-primary">Tambahkan Data</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive mb-3 mx-3">
							    <table id="example" class="table table-sm table-striped" style="width:100%">
                                    <thead>
                                        <tr>
											<th class="text-center">No</th>
											<th>Nama Kategori</th>
											<th class="text-center">Total Jurnal</th>
											@if (Auth::user()->level == 1)
												<th class="text-center" style="width: 100px;">Aksi</th>
											@endif
										</tr>
                                    </thead>
                                    <tbody>
                                    @php 
                                        $no = 1;
                                    @endphp
                                    @foreach($data as $d)
                                    	<tr>
											<td class="text-center">{{ $no++ }}</td>
											<td>{{ $d->nama_kategori}}</td>
											@php
												if ($d->total == 1) {
													$cek = DB::table('t_kategori')->where('nama_kategori', $d->nama_kategori)->first();
													$cek->id_jurnal == NULL ? $total = 0 : $total = $d->total;
												} else {
													$total = $d->total;
												}
											@endphp
											<td class="text-center">{{ $total }}</td>
											@if (Auth::user()->level == 1)
												<td class="table-action text-center" style="width: 100px">
													<a type="button" data-toggle="modal" data-target="#edit{{ $no  }}">
														<button class="btn btn-sm btn-primary" style="border-radius: 5px"><i class="fas fa-edit"></i></button>
													</a>
													<a type="button" data-toggle="modal" data-target="#hapus{{ $no  }}">
														<button class="btn btn-sm btn-danger" style="border-radius: 5px"><i class="fas fa-trash"></i></button>
													</a>
												</td>
											@endif
										</tr>
                                        {{-- Modal Edit --}}
    									<div class="modal fade" id="edit{{ $no }}" tabindex="-1" role="dialog" aria-hidden="true">
    										<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    											<div class="modal-content">
    												<div class="modal-header">
    													<h5 class="modal-title"><strong>Edit Kategori</strong></h5>
    													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    														<span aria-hidden="true">&times;</span>
    													</button>
    												</div>
    												<form action="/detail_kategori/edit/{{ $d->nama_kategori }}" method="post">
    													{{ csrf_field() }}
    													<div class="modal-body m-2">
    														<div class="mb-3">
    															<label class="form-label" for="inputUsername">Nama Kategori</label>
    															<input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Nama Kategori" value="{{ $d->nama_kategori }}" required>
    														</div>
    														<div class="mb-3">
    															<label class="form-label" for="inputUsername">Total Jurnal</label>
    															<input type="text" class="form-control" id="total_jurnal" name="total_jurnal" placeholder="-" value="{{ $total }}" readonly>
    														</div>
    													</div>
    													<div class="modal-footer">
    														<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    														<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    													</div>
    												</form>
    											</div>
    										</div>
    									</div>
    									{{-- Modal Hapus --}}
    									<div class="modal fade" id="hapus{{ $no }}" tabindex="-1" role="dialog" aria-hidden="true">
    										<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    											<div class="modal-content">
    												<div class="modal-header">
    													<h5 class="modal-title"><strong>Hapus Kategori</strong></h5>
    													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    														<span aria-hidden="true">&times;</span>
    													</button>
    												</div>
    												<div class="modal-body m-3">
    													<p class="mb-0">Yakin ingin menghapus kategori <strong>{{ $d->nama_kategori }}</strong>?</p>
    												</div>
    												<div class="modal-footer">
    													<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    													<form action="/detail_kategori/hapus/{{ $d->nama_kategori }}/{{ $total }}" method="post">
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
                                </table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

@endsection