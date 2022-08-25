@extends('admin.template')
@section('title', 'Riwayat Pencarian')
@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-2">Riwayat Pencarian</h1>
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
										<label><h5 class="card-title">Riwayat Pencarian</label>
									</div>
									<div class="float-end">
										<form action="{{ Route('riwayat_pencarian') }}" method="GET" class="row g-2">
											<div class="col-auto">
												<span>Periode :</span>
											</div>
											<div class="col-auto">
											    <div class="input-group mb-3">
                                                    <input class="form-control form-control-md bg-light border-0" type="month" name="periode" value={{ date('Y-m') }}>
                                                    <!--<span class="input-group-text" id="inputGroup-sizing-sm">Small</span>-->
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                                </div>
												<!--<input class="form-control form-control-md bg-light border-0" type="month" name="periode" value={{ date('Y-m') }}>-->
												<!--<button class="btn btn-primary">cari</button>-->
											</div>
											
										</form>
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
											<th>IP Address</th>
											<th>Kata Kunci</th>
											<th>Waktu Pencarian</th>
										</tr>
                                    </thead>
                                    <tbody>
                                    @php 
                                        $no = 1;
                                    @endphp
                                    @foreach($data as $d)
                                    	<tr>
											<td class="text-center">{{ $no++ }}</td>
											<td>{{ $d->ip_address }}</td>
											<td>{{ $d->kata_kunci }}</td>
											<td>{{ $d->tgl_pencarian }}</td>
										</tr>
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