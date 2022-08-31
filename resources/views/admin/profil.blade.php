@extends('admin.template')
@section('title', 'Profil')
@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-2">
			Pengaturan Profil & Password
		</h1>
		@if(session()->has('sukses'))
			<div class="alert alert-success alert-dismissible" role="alert">
				<div class="alert-message">
					{{ session()->get('sukses') }}
				</div>
			</div>
		@endif
		@if(session()->has('error'))
			<div class="alert alert-danger alert-dismissible" role="alert">
				<div class="alert-message">
					{{ session()->get('error') }}
				</div>
			</div>
		@endif
		@if (count($errors) > 0)
			<div class="alert alert-danger alert-dismissible" role="alert">
				<div class="alert-message">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</div>
			</div>
		@endif
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3" align="center">
					<div class="list-group list-group-horizontal" role="tablist" id="tabMenu">
						<a class="list-group-item list-group-item-action active" data-toggle="tab" href="#profil" role="tab" style="border: none; margin-right: 0.5px; border-top-left-radius: 0px; border-top-right-radius: 0px; border-radius: 0.2rem 0.2rem 0rem 0rem;">
							Profil
						</a>
						<a class="list-group-item list-group-item-action" data-toggle="tab" href="#password" role="tab" style="border: none; border-top-left-radius: 0px; border-top-right-radius: 0px; border-radius: 0.2rem 0.2rem 0rem 0rem;">
							Password
						</a>
					</div>
				</div>
				<div class="tab-content">
					<div class="tab-pane active" id="profil" role="tabpanel">
						<div class="row">
							<div class="col-md-12">
								<div class="card" style="box-shadow: none;">
									<div class="card-body">
										@foreach ($data as $data)
											<form action="{{ Route('edit_profil') }}" method="POST">
												{{ csrf_field() }}
												<div class="mb-3">
													<label class="form-label" for="inputUsername">Email</label>
													<input type="text" class="form-control" id="email" name='email' placeholder="Email" value="{{ $data->email }}" maxlength="40" required>
												</div>
												<div class=" mb-3">
													<label class="form-label" for="inputUsername">Nama Lengkap</label>
													<input type="text" class="form-control" id="nama_admin" name="nama_admin"  placeholder="Nama Lengkap" value="{{ $data->nama_admin }}" maxlength="25" required>
												</div>
												<div class=" mb-3">
													<label class="form-label" for="inputUsername">Posisi</label>
													<input type="text" class="form-control" id="level" name='level' value="{{ $data->level == 1 ? 'Super Admin' : 'Admin' }}" readonly>
												</div>
												<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
											</form>
										@endforeach
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="password" role="tabpanel">
						<div class="card" style="box-shadow: none;">
							<div class="card-body">
								<form action="{{ Route('edit_password') }}" method="POST">
									{{ csrf_field() }}
									<div class="mb-3">
										<label class="form-label">Password Lama</label>
										<input type="password" class="form-control" id="password" name='password' required>
									</div>
									<div class="mb-3">
										<label class="form-label" for="inputPasswordNew">Password Baru</label>
										<input type="password" class="form-control" id="password_baru" name="password_baru" minlength="8" required>
									</div>
									<div class="mb-3">
										<label class="form-label" for="inputPasswordNew2">Konfirmasi Password Baru</label>
										<input type="password" class="form-control" id="password-confirm" name="konfirmasi_password" required>
									</div>
									<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
								</form>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</main>
@endsection