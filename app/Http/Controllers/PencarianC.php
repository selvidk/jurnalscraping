<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataJurnalM;
use App\Models\DataKategoriM;
use App\Models\DataPtM;
use App\Models\DataPJM;
use App\Models\PencarianM;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\Input;
// use Illuminate\Support\Facades\Input;

class PencarianC extends Controller
{
    public function __construct()
    {
        $this->model     = new DataJurnalM();
        $this->kategori  = new DataKategoriM();
        $this->pt        = new DataPtM();
        $this->publikasi = new DataPJM();
        $this->pencarian = new PencarianM();
    }

    public function index()
    {
        $data = $this->model->data()
                ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                ->groupBy('t_jurnal.id_jurnal')
                ->orderBy('t_jurnal.id_jurnal', 'ASC')
                ->paginate(10);
        $kategori = $this->kategori->data()
                ->groupBy('nama_kategori')->get();

        return view('index', ['data' => $data, 'kategori' => $kategori]);
    }

    public function searchKey(Request $request)
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'Tidak diketahui';
        }

        $q  = $request->kata_kunci;
        if ($q == '' && !$request->has('sinta') && !$request->has('kategori') && !$request->has('jadwal')) {
            return redirect('/');
        }
        $q2       = $request->sinta;
        $q3       = $request->kategori;
        $q4       = $request->jadwal;
        
            
        if ($request->has('sinta') && $request->has('kategori') && $request->has('jadwal')) {
            
            $data     = $this->model->data()
                        ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                        ->leftJoin('t_kategori', 't_kategori.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->whereIn('peringkat', $q2)
                        ->whereIn('nama_kategori', $q3)
                        ->whereIn('bulan', $q4)
                        ->where(function($query) use ($q) {
                            $query->where('nama_jurnal', 'LIKE', '%'.$q.'%')
                                  ->orWhere('nama_pt', 'LIKE', '%'.$q.'%');
                                //   ->orWhere('nama_kategori', 'LIKE', '%'.$q.'%');
                        })
                        ->groupBy('t_jurnal.id_jurnal')
                        ->paginate(10)
                        ->appends(request()->query()); 
        } elseif ($request->has('sinta') && $request->has('kategori')) {
            $data     = $this->model->data()
                        ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                        ->leftJoin('t_kategori', 't_kategori.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->whereIn('peringkat', $q2)
                        ->whereIn('nama_kategori', $q3)
                        ->where(function($query) use ($q) {
                            $query->where('nama_jurnal', 'LIKE', '%'.$q.'%')
                                  ->orWhere('nama_pt', 'LIKE', '%'.$q.'%');
                                //   ->orWhere('bulan', 'LIKE', '%'.$q.'%');
                        })
                        ->groupBy('t_jurnal.id_jurnal')
                        ->paginate(10)
                        ->appends(request()->query());
        } elseif ($request->has('sinta') && $request->has('jadwal')) {
            $data     = $this->model->data()
                        ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                        ->leftJoin('t_kategori', 't_kategori.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->whereIn('peringkat', $q2)
                        ->whereIn('bulan', $q4)
                        ->where(function($query) use ($q) {
                            $query->where('nama_jurnal', 'LIKE', '%'.$q.'%')
                                  ->orWhere('nama_pt', 'LIKE', '%'.$q.'%');
                                //   ->orWhere('bulan', 'LIKE', '%'.$q.'%');
                        })
                        ->groupBy('t_jurnal.id_jurnal')
                        ->paginate(10)
                        ->appends(request()->query());
        } elseif ($request->has('kategori') && $request->has('jadwal')) {
            $data     = $this->model->data()
                        ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                        ->leftJoin('t_kategori', 't_kategori.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->whereIn('nama_kategori', $q3)
                        ->whereIn('bulan', $q4)
                        ->where(function($query) use ($q) {
                            $query->where('nama_jurnal', 'LIKE', '%'.$q.'%')
                                  ->orWhere('nama_pt', 'LIKE', '%'.$q.'%');
                                //   ->orWhere('bulan', 'LIKE', '%'.$q.'%');
                        })
                        ->groupBy('t_jurnal.id_jurnal')
                        ->paginate(10)
                        ->appends(request()->query());
        } elseif ($request->has('sinta')) {
            $data     = $this->model->data()
                        ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                        ->leftJoin('t_kategori', 't_kategori.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->whereIn('peringkat', $q2)
                        ->where(function($query) use ($q) {
                            $query->where('nama_jurnal', 'LIKE', '%'.$q.'%')
                                  ->orWhere('nama_pt', 'LIKE', '%'.$q.'%');
                                //   ->orWhere('bulan', 'LIKE', '%'.$q.'%');
                        })
                        ->groupBy('t_jurnal.id_jurnal')
                        ->paginate(10)
                        ->appends(request()->query());
        } elseif ($request->has('kategori')) {
            $data     = $this->model->data()
                        ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                        ->leftJoin('t_kategori', 't_kategori.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->whereIn('nama_kategori', $q3)
                        ->where(function($query) use ($q) {
                            $query->where('nama_jurnal', 'LIKE', '%'.$q.'%')
                                  ->orWhere('nama_pt', 'LIKE', '%'.$q.'%');
                                //   ->orWhere('nama_kategori', 'LIKE', '%'.$q.'%');
                        })
                        ->groupBy('t_jurnal.id_jurnal')
                        ->paginate(10)
                        ->appends(request()->query());
        } elseif ($request->has('jadwal')) {
            $data     = $this->model->data()
                        ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                        ->leftJoin('t_kategori', 't_kategori.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->whereIn('bulan', $q4)
                        ->where(function($query) use ($q) {
                            $query->where('nama_jurnal', 'LIKE', '%'.$q.'%')
                                  ->orWhere('nama_pt', 'LIKE', '%'.$q.'%');
                                //   ->orWhere('nama_kategori', 'LIKE', '%'.$q.'%');
                        })
                        ->groupBy('t_jurnal.id_jurnal')
                        ->paginate(10)
                        ->appends(request()->query());
        } else {
            $data     = $this->model->data()
                        ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                        ->leftJoin('t_kategori', 't_kategori.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')
                        ->where('nama_jurnal', 'LIKE', '%'.$q.'%')
                        ->orWhere('nama_pt', 'LIKE', '%'.$q.'%')
                        ->groupBy('t_jurnal.id_jurnal')
                        // ->orWhere('nama_kategori', 'LIKE', '%'.$q.'%')
                        ->paginate(10)
                        ->appends(request()->query());
                        
            $this->pencarian->data()->insert([
                    'ip_address'    => $ipaddress,
                    'kata_kunci'    => $q,
                    'tgl_pencarian' => date('Y-m-d H:i:s')
                ]);
        }

        $kategori = $this->kategori->data()
                    ->groupBy('nama_kategori')->get();
        session()->flashInput($request->input());
        return view('index', ['data' => $data, 'kategori' => $kategori]);
    }
}
