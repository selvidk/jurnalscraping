<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataJurnalM;
use App\Models\PencarianM;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->jurnal    = new DataJurnalM();
        $this->pencarian = new PencarianM();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function adminHome()
    {
        $jurnal     = $this->jurnal->data()->count();
        $kategori   = $this->jurnal->data()->leftJoin('t_kategori', 't_kategori.id_jurnal', '=', 't_jurnal.id_jurnal')->whereNull('t_kategori.id_jurnal')->count();
        $jadwal     = $this->jurnal->data()->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')->whereNull('t_publikasi_jurnal.id_jurnal')->count();
        $pencarian  = $this->pencarian->data()
                      ->whereMonth('tgl_pencarian', date('m'))
                      ->whereYear('tgl_pencarian', date('Y'))
                      ->count();
        $cek_label  = $this->pencarian->data()
                      ->whereMonth('tgl_pencarian', date('m'))
                      ->whereYear('tgl_pencarian', date('Y'))
                      ->select('kata_kunci', DB::raw('count(*) as total'))
                      ->groupBy('kata_kunci')
                      ->orderBy('total', 'DESC')
                      ->limit(10)
                      ->get();

        if(count($cek_label) == 0) {
            $label   = 'Belum Ada Pencarian'; 
            $total   = 0;
        } else {
            for($i=0; $i<count($cek_label); $i++){
                $label[] = $cek_label[$i]->kata_kunci;
                $total[] = $cek_label[$i]->total;
            }
        }
        
        return view('admin.beranda', ['total_j' => $jurnal, 'kategori_null' => $kategori, 'jadwal_null' => $jadwal, 'pencarian' => $pencarian, 'labels' => $label, 'total' => $total]);
    }
}
