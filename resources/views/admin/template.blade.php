<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5"> -->
	<!-- <meta name="author" content="AdminKit"> -->
	<!-- <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web"> -->

	<!-- <link rel="shortcut icon" href="img/icons/icon-48x48.png" /> -->

	<title>Jurnal Scraping | @yield('title')</title>
	<link rel="stylesheet" href="{{ url('admin/css/app.css')}}" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">

	<link rel="canonical" href="https://demo.adminkit.io/charts-chartjs.html" />
	<link class="js-stylesheet" href="{{ url('admin/css/light.css') }}" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="{{ route('beranda') }}">
					<span class="align-middle">JURNAL SCRAPING</span>
				</a>
				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Menu
					</li>
					<li class="sidebar-item {{ (request()-> is('beranda')) ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('beranda') }}">
            				<i class="align-middle" data-feather="grid"></i> <span class="align-middle">Beranda</span>
            			</a>
					</li>
					@if (auth()->user()->level == 1)
						<li class="sidebar-item {{ (request()-> is('data_user')) ? 'active' : '' }}">
							<a class="sidebar-link" href="/data_user">
								<i class="align-middle" data-feather="users"></i>
								<span class="align-middle">Data User</span>
							</a>
						</li>
					@endif
					<li class="sidebar-item {{ (request()-> is('daftar_pt')) ? 'active' : ((request()-> is('daftar_kategori')) ? 'active' : ((request()-> is('daftar_jurnal')) ? 'active' : ''))}}">
						<a data-target="#data_scraping" data-toggle="collapse" class="sidebar-link collapsed">
              				<i class="align-middle" data-feather="list"></i> <span class="align-middle">Data Scraping</span>
            			</a>
						<ul id="data_scraping" class="sidebar-dropdown list-unstyled collapse {{ (request()-> is('daftar_pt')) ? 'show' : ((request()-> is('daftar_kategori')) ? 'show' : ((request()-> is('daftar_jurnal')) ? 'show' : ''))}}" data-parent="#data_scraping">
							<li class="sidebar-item {{ (request()-> is('daftar_pt')) ? 'active' : '' }}">
								<a class="sidebar-link" href="/daftar_pt">Data Perguruan Tinggi</a>
							</li>
							<li class="sidebar-item {{ (request()-> is('daftar_kategori')) ? 'active' : '' }}">
								<a class="sidebar-link" href="/daftar_kategori">Data Kategori</a>
							</li>
							<li class="sidebar-item {{ (request()-> is('daftar_jurnal')) ? 'active' : '' }}">
								<a class="sidebar-link" href="/daftar_jurnal">Data Jurnal</a>
							</li>
						</ul>
					</li>
					<li class="sidebar-item {{ (request()-> is('riwayat_pencarian')) ? 'active' : '' }}">
						<a class="sidebar-link" href="/riwayat_pencarian">
              				<i class="align-middle" data-feather="clock"></i> <span class="align-middle">Riwayat Pencarian</span>
            			</a>
					</li>
					<li class="sidebar-item {{ (request()-> is('pengaturan')) ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ Route('pengaturan') }}">
              				<i class="align-middle" data-feather="settings"></i> <span class="align-middle">Pengaturan</span>
            			</a>
					</li>
				</ul>
			</div>
		</nav>
		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle d-flex">
          			<i class="hamburger align-self-center"></i>
        		</a>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-toggle="dropdown">
                				<i class="align-middle" data-feather="settings"></i>
              				</a>
							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-toggle="dropdown">
								<span class="text-dark">{{ Auth::user()->nama_admin }}</span>
              				</a>
							<div class="dropdown-menu dropdown-menu-right">
								<a class="dropdown-item {{ (request()-> is('profil')) ? 'active' : '' }}" href="{{ route('profil') }}"><i class="align-middle mr-1" data-feather="user"></i> Profil</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" type="button" data-toggle="modal" data-target="#logout">Log out</a>
							</div>
						</li>
					</ul>
				</div>
				<div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><strong>Log Out</strong></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body m-3">
								<p class="mb-0">Yakin ingin logout / keluar dari sistem?</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
								<a class="btn btn-primary" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                </a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
								</form>
							</div>
						</div>
					</div>
				</div>
			</nav>

			<!-- Content -->
            @yield('content')

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-left">
							<p class="mb-0">
								Copyright &copy; Jurnal Scraping 2022
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
	<script src="{{ url('admin/js/app.js')}}"></script>
	<!--<script src=https://code.jquery.com/jquery-3.5.1.js></script>-->
    <script src=https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js></script>
    <script src=https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable();
            // var example_length = document.getElementById("example_length").innerHTML;
            // document.getElementById("example_length").innerHTML = example_length.replace("Show", "Tampilkan").replace("entries", "data");
            // var example_filter = document.getElementById("example_filter").innerHTML;
            // document.getElementById("example_filter").innerHTML = example_filter.replace("Search:", "Cari:");
            // var example_info = document.getElementById("example_info").innerHTML;
            // document.getElementById("example_info").innerHTML = example_info.replace("Showing", "Menampilkan").replace("to", "-").replace("of", "dari").replace("entries", "data");
            // var example_previous = document.getElementById("example_previous").innerHTML;
            // document.getElementById("example_previous").innerHTML = example_previous.replace("Previous", "<");
            // var example_next = document.getElementById("example_next").innerHTML;
            // document.getElementById("example_next").innerHTML = example_next.replace("Next", ">");
        });
    </script>
	<script>
		function previewFile(input){
			var file=$("input[type=file]").get(0).files[0];
			if(file)
			{
				var reader = new FileReader();
				reader.onload = function(){
					$('#previewImg').attr("src",reader.result);
				}
				reader.readAsDataURL(file);
			}
		}
	</script>
	<script>
		function back(){
			window.history.back();
		}
	</script>

	{{-- Tab Spesifik --}}
	<script>
		//redirect to specific tab
		$(document).ready(function () {
			$('#tabMenu a[href="#{{ old('tab') }}"]').tab('show')
		});
	</script>
</body>

</html>