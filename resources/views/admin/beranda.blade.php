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
										<span class="text-muted">Dalam Bulan ini</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
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