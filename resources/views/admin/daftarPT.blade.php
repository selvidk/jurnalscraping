@extends('admin.template')
@section('title', 'Daftar Perguruan Tinggi')
@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-2">Data Perguruan Tinggi</h1>
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
			{{-- <div class="col-md-3 col-xl-2">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">Toko</h5>
					</div>
					<div class="list-group list-group-flush" role="tablist">
						<a class="list-group-item list-group-item-action active" data-toggle="list" href="#produk" role="tab">
							Produk
						</a>
						<a class="list-group-item list-group-item-action" data-toggle="list" href="#kategori" role="tab">
							Kategori
						</a>
					</div>
				</div>
			</div> --}}
			<div class="col-md-12">
				<div class="tab-content">
					<div class="tab-pane fade show active" id="produk" role="tabpanel">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-sm-12">
										<label><h5 class="card-title">Data Perguruan Tinggi
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
													<h5 class="modal-title"><strong>Tambah Perguruan Tinggi</strong></h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form action="{{ Route('pt_tambah') }}" method="POST">
													{{ csrf_field() }}
													<div class="modal-body m-2">
														<div class="mb-3">
															<label class="form-label" for="inputUsername">Nama Perguruan Tinggi</label>
															<input type="text" class="form-control" id="nama_pt" name="nama_pt" placeholder="Nama Perguruan Tinggi" required>
														</div>
														<div class="mb-3">
															<label class="form-label" for="inputUsername">Alamat</label>
															<textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control"></textarea>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
														<button type="submit" class="btn btn-primary">Tambahkan Data</button>
													</div>
												</form>
												{{-- <div class="modal-body m-3">
													<p class="mb-0">Yakin ingin menghapus kategori <strong></strong>?</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
													<form action="/deleteKategori/" method="post">
														{{ csrf_field() }}
														@method('delete')
														<button type="submit" class="btn btn-primary">Hapus</button>
													</form>
												</div> --}}
											</div>
										</div>
									</div>
								</div>
								{{-- <div class="row">
									<div class="col-sm-12 col-md-4 mt-1">
										<div id="example_filter" class="dataTables_filter">
											<input type="search" class="form-control form-control-sm" placeholder="Search" aria-controls="example">
										</div>
									</div>
								</div> --}}
							</div>
							<div class="table-responsive mb-3 mx-3">
							    <table id="example" class="table table-sm table-striped" style="width:100%">
                                    <thead>
                                        <tr>
											<th class="text-center">No</th>
											<th>Nama Perguruan Tinggi</th>
											<th>Alamat</th>
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
											<td>{{ $d->nama_pt}}</td>
											<td>{{ $d->alamat }}</td>
											@if (Auth::user()->level == 1)
												<td class="table-action text-center" style="width: 5px">
													<a type="button" data-toggle="modal" data-target="#edit{{ $d->id_pt }}">
														<button class="btn btn-sm btn-primary" style="border-radius: 5px"><i class="fas fa-edit"></i></button>
													</a>
													<a type="button" data-toggle="modal" data-target="#hapus{{ $d->id_pt }}">
														<button class="btn btn-sm btn-danger" style="border-radius: 5px"><i class="fas fa-trash"></i></button>
													</a>
												</td>
											@endif
										</tr>
                                        {{-- Modal Edit --}}
										<div class="modal fade" id="edit{{ $d->id_pt }}" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title"><strong>Edit Perguruan Tinggi</strong></h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<form action="/detail_pt/edit/{{ $d->id_pt }}" method="post">
														{{ csrf_field() }}
														<div class="modal-body m-2">
															<div class="mb-3">
																<label class="form-label" for="inputUsername">Nama Perguruan Tinggi</label>
																<input type="text" class="form-control" id="nama_pt" name="nama_pt" placeholder="Nama Perguruan Tinggi" value="{{ $d->nama_pt }}" required>
															</div>
															<div class="mb-3">
																<label class="form-label" for="inputUsername">Alamat</label>
																<textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control">{{ $d->alamat }}</textarea>
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
										<div class="modal fade" id="hapus{{ $d->id_pt }}" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title"><strong>Hapus Perguruan Tinggi</strong></h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-3">
														<p class="mb-0">Yakin ingin menghapus perguruan tinggi <strong>{{ $d->nama_pt }}</strong>?</p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
														<form action="/detail_pt/hapus/{{ $d->id_pt }}" method="post">
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