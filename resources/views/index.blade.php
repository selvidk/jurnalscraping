<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Jurnal Scraping</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ url('search/css/styles.css')}}" rel="stylesheet" />
        {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <style>
            /* @media screen and (min-width: 576px) {.filter {display: none;}} */
            /* @media screen and (min-width: 768px) {.filter {display: none;}}
            @media screen and (min-width: 992px) {.filter {display: none;}} */
            /* @media (min-width: 768px) {
                .collapse:not(.show) {
                    display: none;
                }
            } */
            /* @media only screen and (min-width: 1400px) {.filter {display: none;}}
            @media only screen and (min-width: 1200px){.filter {display: block;}} */
        </style>
    </head>
    <body>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container"  style="padding-left: 25px; padding-right: 25px;">
                <a class="navbar-brand" href="/">JURNAL SCRAPING</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="/tentang">Tentang</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-5" style="padding-top: 30px">
            <div class="row d-flex justify-content-center" style="padding-left: 25px; padding-right: 25px;">
                <!-- Side widgets-->
                <div class="col-lg-12 mt-3 mb-3">
                    {{-- <div class="sticky-top" style="top:100px;"> --}}
                        <!-- Search widget-->
                        {{-- <div class="card mb-4"> --}}
                            {{-- <div class="card-header">Pencarian</div>
                            <div class="card-body"> --}}
                                <form action="/pencarian" method="GET">
                                    <div class="input-group">
                                        <input class="form-control" type="search" placeholder="Lakukan pencarian jurnal, nama perguruan tinggi atau lainnya" aria-label="Pencarian" aria-describedby="button-search" name="kata_kunci" value="{{ Route::is('pencarian') ? NULL : old('kata_kunci') }}" />
                                        <button class="btn btn-primary" type="submit">Cari</button>
                                    </div>
                                </form>
                            {{-- </div> --}}
                        {{-- </div> --}}
                    {{-- </div> --}}
                </div>
            </div>
        </div>
        <!-- Page content-->
        <div class="container mb-5">
            <div class="row" style="padding-left: 25px; padding-right: 25px;">
                <!-- Side widgets-->
                <div class="col-lg-4 mt-3 mb-3">
                    <div class="sticky-top" style="top:90px;">
                        <!-- Search widget-->
                        {{-- <div class="card mb-4">
                            <div class="card-header">Pencarian</div>
                            <div class="card-body">
                                <form action="/pencarian" method="GET">
                                    <div class="input-group">
                                        <input class="form-control" type="search" placeholder="Pencarian" aria-label="Pencarian" aria-describedby="button-search" name="kata_kunci" value="{{ Route::is('pencarian') ? NULL : old('kata_kunci') }}"/>
                                    </div>
                                </form>
                            </div>
                        </div> --}}
                        <!-- Categories widget-->
                        <div class="card mb-4">
                            <form action="/pencarian" method="GET">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-12">Filter
                                            <button type="submit" class="btn btn-sm btn-primary float-end">Terapkan</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <a data-bs-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <span style="font-weight: 470"><i class="bi bi-arrow-down-up"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body collapse show" id="filter">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            {{-- <a data-bs-toggle="collapse" href="#akreditasi" role="button" aria-expanded="false" aria-controls="collapseExample"> --}}
                                                <span style="font-weight: 470">Jurnal Terakreditasi</span>
                                            {{-- </a> --}}
                                        </div>
                                    </div>
                                    <div class="row" id="akreditasi">
                                        <div class="col-sm-12">
                                            <input type="hidden" name="kata_kunci" value="{{ Route::is('pencarian') ? NULL : old('kata_kunci') }}">
                                            <div class="row">
                                                @for ($i = 1; $i <= 6; $i++)
                                                    <div class="col-sm-4">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="sinta[]" name="sinta[]" value="{{ $i }}"
                                                                @if(is_array(old('sinta')) && in_array($i, old('sinta')) && !Route::is('pencarian')) 
                                                                    checked 
                                                                @endif
                                                            >
                                                            <label class="form-check-label" for="inlineCheckbox1">S{{ $i }}</label>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                            {{-- <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                <label class="form-check-label" for="inlineCheckbox1">1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                <label class="form-check-label" for="inlineCheckbox2">2</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                <label class="form-check-label" for="inlineCheckbox3">3</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option4">
                                                <label class="form-check-label" for="inlineCheckbox4">4</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="option5">
                                                <label class="form-check-label" for="inlineCheckbox5">5</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox6" value="option6">
                                                <label class="form-check-label" for="inlineCheckbox6">6</label>
                                            </div> --}}
                                        </div>
                                    </div>
                                    {{-- <hr class="mb-0 mt-2 border border-1 border border-dark"> --}}
                                    <div class="row">
                                        <div class="col-md-12 mb-2 mt-2">
                                            <span style="font-weight: 470">Jadwal Publikasi</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @for ($j = 1; $j <= 12; $j++)
                                            @switch($j)
                                                @case(1)
                                                    @php $bln = "Januari"; @endphp
                                                    @break
                                                @case(2)
                                                    @php $bln = "Februari"; @endphp
                                                    @break
                                                @case(3)
                                                    @php $bln = "Maret"; @endphp
                                                    @break
                                                @case(4)
                                                    @php $bln = "April"; @endphp
                                                    @break
                                                @case(5)
                                                    @php $bln = "Mei"; @endphp
                                                    @break
                                                @case(6)
                                                    @php $bln = "Juni"; @endphp
                                                    @break
                                                @case(7)
                                                    @php $bln = "Juli"; @endphp
                                                    @break
                                                @case(8)
                                                    @php $bln = "Agustus"; @endphp
                                                    @break
                                                @case(9)
                                                    @php $bln = "September"; @endphp
                                                    @break
                                                @case(10)
                                                    @php $bln = "Oktober"; @endphp
                                                    @break
                                                @case(11)
                                                    @php $bln = "November"; @endphp
                                                    @break
                                                @case(12)
                                                    @php $bln = "Desember"; @endphp
                                                    @break
                                            @endswitch
                                            <div class="col-sm-6">
                                                <div div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="jadwal[]" name="jadwal[]" value="{{ $j }}"
                                                        @if(is_array(old('jadwal')) && in_array($j, old('jadwal')) && !Route::is('pencarian')) 
                                                            checked 
                                                        @endif
                                                    >
                                                    <label class="form-check-label">{{ $bln }}</label>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-2 mt-2">
                                            <span style="font-weight: 470">Kategori Jurnal</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @for ($i = 0; $i < count($kategori); $i++)
                                            <div class="col-sm-6">
                                                <div div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="kategori[]" name="kategori[]" value="{{ $kategori[$i]->nama_kategori }}" 
                                                        @if(is_array(old('kategori')) && in_array($kategori[$i]->nama_kategori, old('kategori')) && !Route::is('pencarian')) checked @endif
                                                    >
                                                    <label class="form-check-label">{{ $kategori[$i]->nama_kategori }}</label>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- } --}}
                       
                        <!-- Side widget-->
                        {{-- <div class="card mb-4">
                            <div class="card-header">Filter Kategori Jurnal</div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($kategori as $k)
                                        <div class="col-sm-6">
                                            <div div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="{{ $k->id_kategori }}">
                                                <label class="form-check-label">{{ $k->nama_kategori }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-8 mt-3">
                    {{-- <div class=" position-fixed" style="top:100px"> --}}
                    <!-- Post content-->
                    <article>
                        <!-- Post header-->
                        <header class="mb-2">
                            <div class="row d-flex justify-content-between">
                                <div class="col-md-5">
                                    <span class="text-muted fst-italic">Total Data : {{ $data->total() }}</span></br>
                                    <span class="text-muted fst-italic">{{ ceil($data->total()/10) }} Halaman</span>
                                </div>
                                <div id="pagi" class="col-md-7 d-flex justify-content-end mt-1">
                                    {{ $data->onEachSide(1)->links() }}
                                    {{-- {{ $data->appends(request()->query())->links('vendor.pagination.custom') }} --}}
                                </div>
                            </div>
                        </header>
                    </article>
                    {{-- </div> --}}
                    <section class="mb-5">
                        <div class="card" style="border: none;" >
                            {{-- <div class="card-body"> --}}
                                <div class="row">
                                    @foreach ($data as $key=>$d)
                                        @php
                                            $j_kat = DB::table('t_kategori')->where('id_jurnal', $d->id_jurnal)->get();
                                            $j_jdw = DB::table('t_publikasi_jurnal')->where('id_jurnal', $d->id_jurnal)->get();
                                        @endphp
                                        <div class="col-md-6 mb-3">
                                            <div class="card bg-light" style="border: none">
                                                <div class="card-body p-3 px-4">
                                                    <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4"><span>S{{ $d->peringkat }}</span></div>
                                                    <a href="{{ $d->url }}" style="text-decoration: none;" target="_blank"><h6 class="fw-bold">{{ $d->nama_jurnal }}</h6></a>
                                                    {{-- <div class="small text-muted">- Client Name, Location</div> --}}
                                                    <h6 class="mb-0">{{ $d->nama_pt }}</h6><hr class="mb-0 mt-2 border border-1 border border-primary">
                                                    <ul class="list-unstyled mt-1 mb-0">
                                                        <li class="mb-2">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0"><i class="bi bi-signpost"></i></div>
                                                                <div class="ms-2">
                                                                    @if (count($j_kat) == 0 || $d->id_jurnal == null)
                                                                        <span>-</span>
                                                                    @else
                                                                        @for ($i = 0; $i < count($j_kat); $i++)
                                                                            <span>{{ $j_kat[$i]->nama_kategori }}
                                                                            @if ($i+1 != count($j_kat))
                                                                                |
                                                                            @endif
                                                                            </span>
                                                                        @endfor
                                                                    @endif
                                                                    {{-- @foreach ($j_kat as $j_k)
                                                                    <span class="badge bg-secondary">{{ $j_k->nama_kategori }} </span>
                                                                    @endforeach --}}
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0"><i class="bi bi-calendar-week-fill"></i></div>
                                                                <div class="ms-2">
                                                                    @if (count($j_jdw) == 0 || $d->id_jurnal == null)
                                                                        <span>-</span>
                                                                    @else
                                                                        @for ($i = 0; $i < count($j_jdw); $i++)
                                                                        @switch($j_jdw[$i]->bulan)
                                                                            @case(1)
                                                                                @php $bln = "Januari"; @endphp @break
                                                                            @case(2)
                                                                                @php $bln = "Februari"; @endphp @break
                                                                            @case(3)
                                                                                @php $bln = "Maret"; @endphp @break
                                                                            @case(4)
                                                                                @php $bln = "April"; @endphp @break
                                                                            @case(5)
                                                                                @php $bln = "Mei"; @endphp @break
                                                                            @case(6)
                                                                                @php $bln = "Juni"; @endphp @break
                                                                            @case(7)
                                                                                @php $bln = "Juli"; @endphp @break
                                                                            @case(8)
                                                                                @php $bln = "Agustus"; @endphp @break
                                                                            @case(9)
                                                                                @php $bln = "September"; @endphp @break
                                                                            @case(10)
                                                                                @php $bln = "Oktober"; @endphp @break
                                                                            @case(11)
                                                                                @php $bln = "November"; @endphp @break
                                                                            @case(12)
                                                                                @php $bln = "Desember"; @endphp @break
                                                                        @endswitch
                                                                            <span>{{ $bln }}
                                                                                @if ($i+1 != count($j_jdw))
                                                                                |
                                                                                @endif
                                                                            </span>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row d-flex justify-content-between">
                                            <div class="col-md-5">
                                                <span class="text-muted fst-italic">Total Data : {{ $data->total() }}</span></br>
                                                <span class="text-muted fst-italic">{{ ceil($data->total()/10) }} Halaman</span>
                                            </div>
                                            <div id="pagi2" class="col-md-7 d-flex justify-content-end mt-1">
                                                {{-- <nav aria-label="Page navigation example"> --}}
                                                {{-- {{ $data->links('vendor.pagination.custom') }} --}}
                                                {{ $data->onEachSide(1)->links() }}
                                                {{-- {!! $data->onEachSide(3)->links('vendor.pagination.custom') !!} --}}
                                                {{-- </nav> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{-- </div> --}}
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Jurnal Scraping 2022</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{ url('search/js/scripts.js') }}"></script>
        <script>
            function myFunction(x) {
              if (x.matches) { // If media query matches
              	var element = document.getElementById("pagi");
              	element.classList.remove("justify-content-end");
                element.classList.add("justify-content-center");
                var element2 = document.getElementById("pagi2");
                element2.classList.remove("justify-content-end");
                element2.classList.add("justify-content-center");
              } else {
                var element = document.getElementById("pagi");
              	element.classList.remove("justify-content-center");
                element.classList.add("justify-content-end");
                var element2 = document.getElementById("pagi2");
                element2.classList.remove("justify-content-center");
                element2.classList.add("justify-content-end");
              }
            }
            
            var x = window.matchMedia("(max-width: 500px)")
            myFunction(x) // Call listener function at run time
            x.addListener(myFunction) // Attach listener function on state changes
        </script>
    </body>
</html>
