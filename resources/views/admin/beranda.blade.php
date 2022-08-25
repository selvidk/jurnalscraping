@extends('admin.template')
@section('title', 'Beranda')
@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-2">Beranda</h1>
		<div class="row">
			<div class="col-md-4 d-flex">
				<div class="w-100">
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col mt-0">
											<h5 class="card-title">Total Jurnal</h5>
										</div>
										<div class="col-auto">
											<div class="stat text-primary">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book align-middle"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
											</div>
										</div>
									</div>
									<h1 class="mt-1 mb-3">{{ $total_j }}</h1>
									<div class="mb-0">
										{{-- <span class="badge badge-danger-light"> <i class="mdi mdi-arrow-bottom-right"></i> -2.25% </span> --}}
										<span class="text-muted">Saat Ini</span>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col mt-0">
											<h5 class="card-title">Jumlah Pencarian</h5>
										</div>
										<div class="col-auto">
											<div class="stat text-primary">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search align-middle"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
											</div>
										</div>
									</div>
									<h1 class="mt-1 mb-3">{{ $pencarian }}</h1>
									<div class="mb-0">
										{{-- <span class="badge badge-success-light"> <i class="mdi mdi-arrow-bottom-right"></i> 5.25% </span> --}}
										<span class="text-muted">Dalam Bulan ini</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
						 <!--   <div class="card">-->
							<!--	<div class="card-body">-->
							<!--		<div class="row">-->
							<!--			<div class="col mt-0">-->
							<!--				<h5 class="card-title">Tanpa Kategori</h5>-->
							<!--			</div>-->
							<!--			<div class="col-auto">-->
							<!--				<div class="stat text-primary">-->
							<!--					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users align-middle"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>-->
							<!--				</div>-->
							<!--			</div>-->
							<!--		</div>-->
							<!--		<h1 class="mt-1 mb-3">{{ $kategori_null }}</h1>-->
							<!--		<div class="mb-0">-->
							<!--			{{-- <span class="badge badge-primary-light"> <i class="mdi mdi-arrow-bottom-right"></i> -3.65% </span> --}}-->
							<!--			<span class="text-muted">Dalam 30 Hari Terakhir</span>-->
							<!--		</div>-->
							<!--	</div>-->
							<!--</div>-->
						    
							<!--<div class="card">-->
							<!--	<div class="card-body">-->
							<!--		<div class="row">-->
							<!--			<div class="col mt-0">-->
							<!--				<h5 class="card-title">Tanpa Jadwal Publikasi</h5>-->
							<!--			</div>-->
							<!--			<div class="col-auto">-->
							<!--				<div class="stat text-primary">-->
							<!--					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle align-middle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>-->
							<!--				</div>-->
							<!--			</div>-->
							<!--		</div>-->
							<!--		<h1 class="mt-1 mb-3">{{ $jadwal_null }}</h1>-->
							<!--		<div class="mb-0">-->
							<!--			{{-- <span class="badge badge-success-light"> <i class="mdi mdi-arrow-bottom-right"></i> 6.65% </span> --}}-->
							<!--			<span class="text-muted">Dalam Daftar Jurnal</span>-->
							<!--		</div>-->
							<!--	</div>-->
							<!--</div>-->
							
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">Diagram Batang</h5>
						<h6 class="card-subtitle text-muted">Tren Pencarian Bulan Ini</h6>
					</div>
					<div class="card-body">
						<div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
							<canvas id="chartjs-bar" width="816" height="600" style="display: block; height: 300px; width: 408px;" class="chartjs-render-monitor"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
		{{-- <div class="row">
			<div class="col-xl-12 col-xxl-5 d-flex">
				<div class="w-100">
					<div class="row">
						<div class="col-sm-6">
							<div class="card">
								<div class="card-body">
									<h5 class="card-title mb-4">Pesanan</h5>
									<h1 class="mt-1 mb-3"></h1>
									<div class="mb-1">
										<!-- <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> -2.25% </span> -->
										<span class="text-muted"></span>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col mt-0">
											<h5 class="card-title">Sales</h5>
										</div>
										<div class="col-auto">
											<div class="stat text-primary">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck align-middle"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
											</div>
										</div>
									</div>
									<h1 class="mt-1 mb-3">2.382</h1>
									<div class="mb-0">
										<span class="badge badge-primary-light"> <i class="mdi mdi-arrow-bottom-right"></i> -3.65% </span>
										<span class="text-muted">Since last week</span>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-body">
									<h5 class="card-title mb-4">Produk</h5>
									<h1 class="mt-1 mb-3"></h1>
									<div class="mb-1">
										<span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="card">
								<div class="card-body">
									<h5 class="card-title mb-4">Penjualan</h5>
									<h1 class="mt-1 mb-3"></h1>
									<div class="mb-1">
										<!-- <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> +3% </span> -->
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-body">
									<h5 class="card-title mb-4">Pengunjung</h5>
									<h1 class="mt-1 mb-3">Coming Soon</h1>
									<div class="mb-1">
										<!-- <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> 5.25% </span> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> --}}

			<!-- <div class="col-xl-6 col-xxl-7">
				<div class="card flex-fill w-100">
					<div class="card-header">

						<h5 class="card-title mb-0">Recent Movement</h5>
					</div>
					<div class="card-body py-3">
						<div class="chart chart-sm">
							<canvas id="chartjs-dashboard-line"></canvas>
						</div>
					</div>
				</div>
			</div> -->
		{{-- </div> --}}
	</div>
</main>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Bar chart
		new Chart(document.getElementById("chartjs-bar"), {
			type: "bar",
			data: {
				labels: <?php echo json_encode($labels); ?>,
				datasets: [{
					backgroundColor: window.theme.primary,
					borderColor: window.theme.primary,
					hoverBackgroundColor: window.theme.primary,
					hoverBorderColor: window.theme.primary,
					data: <?php echo json_encode($total); ?>,
					barPercentage: .75,
					categoryPercentage: .5
				}]
			},
			options: {
				maintainAspectRatio: false,
				legend: {
					display: false
				},
				scales: {
					yAxes: [{
						gridLines: {
							display: false
						},
						stacked: false,
						ticks: {
							stepSize: 20
						}
					}],
					xAxes: [
                        {
                            stacked: false,
    						gridLines: {
    							color: "transparent"
    						},
                            // ticks: {
                            //     callback: function(label, index, labels) {
                            //       if (/\s/.test(label)) {
                            //         return label.split(" ");
                            //       }else{
                            //         return label;
                            //       }              
                            //     }
                            // }
                        }
                    ]

				}
			}
		});
	});
</script>
@endsection