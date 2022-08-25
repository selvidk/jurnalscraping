@extends('admin.template')
@section('title', 'Data Jurnal')
@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-2">Data Jurnal</h1>
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
										<label><h5 class="card-title">Data Jurnal
											<!--@if (Auth::user()->level == 1)-->
											<!--	<a type="button" data-toggle="modal" data-target="#tambah"><button class="btn btn-pill btn-primary btn-sm ml-2"><i class="fas fa-plus"></i></i></button></a>-->
											<!--@endif-->
											</h5>
										</label>
									</div>
									{{-- Modal Tambah --}}
									<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-md modal-dialog-centered" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title"><strong>Tambah Jurnal</strong></h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form action="{{ Route('jurnal_tambah') }}" method="POST">
													{{ csrf_field() }}
													<div class="modal-body m-2">
														<div class="mb-3">
															<label class="form-label" for="inputUsername">Nama Jurnal</label>
															<input type="text" class="form-control" id="nama_jurnal" name="nama_jurnal" placeholder="Nama Jurnal" required>
														</div>
														<div class="mb-3">
															<label class="form-label" for="inputUsername">Perguruan Tinggi</label>
															<input type="text" class="form-control" id="pt" name="pt" placeholder="Perguruan Tinggi" required>
														</div>
														{{-- <div class="mb-3">
															<label class="form-label" for="inputUsername">URL</label>
															<input type="url" class="form-control" id="url" name="url" placeholder="URL" required>
														</div> --}}
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
									<div class="col-sm-4 mt-1">
										<form action="/">
											<div class="col-auto">
												<input type="text" class="form-control bg-light rounded-2 border-0" placeholder="Search..">
											</div>
										</form>
									</div>
								</div> --}}
							</div>
							
							<div class="table-responsive mb-3 mx-3">
							    <table id="example" class="table table-sm table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Peringkat</th>
                                            <th>Nama Jurnal</th>
                                            <th>Perguruan Tinggi</th>
											<th class="text-center" style="width:100px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php 
                                        $no = 1;
                                    @endphp
                                    @foreach($data as $d)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="text-center">S{{ $d->peringkat }}</td>
                                            <td>{{ $d->nama_jurnal }}</td>
                                            <td>{{ $d->nama_pt }}</td>
                                            <td style="width:100px" class="table-action text-center">
												<a href="/detail_jurnal/{{ $d->id_jurnal }}">
													<button class="btn btn-sm btn-primary" style="border-radius: 5px"><i class="fas fa-edit"></i></button>
												</a>
												@if (Auth::user()->level == 1)
    												<a type="button" data-toggle="modal" data-target="#hapus{{ $d->id_jurnal }}">
    													<button class="btn btn-sm btn-danger" style="border-radius: 5px"><i class="fas fa-trash"></i></button>
    												</a>
												@endif
											</td>
                                        </tr>
                                        {{-- Modal Hapus --}}
										<div class="modal fade" id="hapus{{ $d->id_jurnal }}" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title"><strong>Hapus Jurnal</strong></h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-3">
														<p class="mb-0">Yakin ingin menghapus jurnal <strong>{{ $d->nama_jurnal }}</strong>?</p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
														<form action="/daftar_jurnal/hapus/{{ $d->id_jurnal }}" method="post">
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