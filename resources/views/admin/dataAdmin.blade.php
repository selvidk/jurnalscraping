@extends('admin.template')
@section('title', 'Data User')
@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-2">Data User</h1>
		@if(session()->has('sukses'))
			<div class="alert alert-success alert-dismissible" role="alert">
				<div class="alert-message">
					{{ session()->get('sukses') }}
				</div>
			</div>
		@endif
		@if (count($errors) > 0)
			<div class="alert alert-danger alert-dismissible" role="alert">
				<div class="alert-message">
					@foreach ($errors->all() as $error)
						{{ $error }}</br>
					@endforeach
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
										<label><h5 class="card-title">Data User
											<a type="button" data-toggle="modal" data-target="#tambah"><button class="btn btn-pill btn-primary btn-sm ml-2"><i class="fas fa-plus"></i></i></button></a>
											</h5>
										</label>
									</div>
									{{-- Modal Tambah --}}
									<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title"><strong>Tambah Data User</strong></h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form action="{{ Route('tambah_admin') }}" method="POST">
													{{ csrf_field() }}
													<div class="modal-body m-2">
														<div class="mb-3">
															<label class="form-label" for="inputUsername">Nama User</label>
															<input type="text" class="form-control" id="nama_admin" name="nama_admin" placeholder="Nama User" maxlength="25" required>
														</div>
														<div class="mb-3">
															<label class="form-label" for="inputUsername">Email</label>
															<input type="email" class="form-control" id="email" name="email" placeholder="Email" maxlength="40" required>
														</div>
														<div class="mb-3">
															<label class="form-label" for="inputUsername">Level</label>
															<select name="level" id="level" class="form-control" required>
																<option value="1">Super Admin</option>
																<option value="2">Admin</option>
															</select>
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
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<thead class="text-center">
										<tr>
											<th>No.</th>
											<th>Nama User</th>
											<th>Email</th>
											<th>Level</th>
											<th colspan="2">Aksi</th>
										</tr>
									</thead>
									@php $no = 1; @endphp
									@foreach ($data as $data)
										<tbody class="text-center">
											<tr>
												<td>{{ $no++ }}</td>
												<td>{{ $data->nama_admin }}</td>
												<td>{{ $data->email }}</td>
												<td>{{ $data->level == 1 ? 'Super Admin' : 'Admin' }}</td>
												<td class="table-action" style="width: 5px">
													<a type="button" data-toggle="modal" data-target="#edit{{ $no }}">
														<button class="btn btn-sm btn-primary" style="border-radius: 5px"><i class="fas fa-edit"></i></button>
													</a>
												</td>
												<td class="table-action" style="width: 5px">
													<a type="button" data-toggle="modal" data-target="#hapus{{ $no }}">
														<button class="btn btn-sm btn-danger" style="border-radius: 5px"><i class="fas fa-trash"></i></button>
													</a>
												</td>
											</tr>
										</tbody>
										{{-- Modal Edit --}}
										<div class="modal fade" id="edit{{ $no }}" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title"><strong>Edit Data User</strong></h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<form action="{{ Route('edit_admin', $data->id) }}" method="post">
														{{ csrf_field() }}
														<div class="modal-body m-2">
															<div class="mb-3">
																<label class="form-label" for="inputUsername">Nama User</label>
																<input type="text" class="form-control" id="nama_admin" name="nama_admin" placeholder="Nama User" value="{{ $data->nama_admin }}" maxlength="25" required>
															</div>
															<div class="mb-3">
																<label class="form-label" for="inputUsername">Email</label>
																<input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{ $data->email }}" maxlength="40" required>
															</div>
															<div class="mb-3">
																<label class="form-label" for="inputUsername">Level</label>
																<select name="level" id="level" class="form-control" required>
																	<option value="{{ $data->level }}" selected>{{ $data->level == 1 ? 'Super Admin' : 'Admin' }}</option>
																	<option value="1">Super Admin</option>
																	<option value="2">Admin</option>
																</select>
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
														<h5 class="modal-title"><strong>Hapus Data User</strong></h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-3">
														<p class="mb-0">Yakin ingin menghapus data user  dengan nama <strong>{{ $data->nama_admin }}</strong>?</p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
														<form action="{{ Route('hapus_admin', $data->id) }}" method="post">
															{{ csrf_field() }}
															@method('delete')
															<button type="submit" class="btn btn-primary">Hapus</button>
														</form>
													</div>
												</div>
											</div>
										</div>
									@endforeach
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