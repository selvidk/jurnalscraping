<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataJurnalM;
use App\Models\DataKategoriM;
use App\Models\DataPtM;
use App\Models\DataPJM;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DataJurnalC extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->model = new DataJurnalM();
        $this->kategori = new DataKategoriM();
        $this->pt = new DataPtM();
        $this->publikasi = new DataPJM();
    }

    public function index()
    {
        // $data = $this->model->data()
        //         ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
        //         ->paginate(10);
        $data = $this->model->data()
                ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                ->get();

        return view('admin.daftarJurnal', ['data' => $data]);
    }

    public function create(Request $request)
    {
        // $input = $request->validate([
        //     'nama_jurnal'   => 'required|max:100',
        //     'url'           => $request->url,
        //     'peringkat'     => $request->peringkat,
        //     'id_pt'         => Hash::make($request->email),
        // ]);

        // if ($input->fails()) {
        //     Session::flash('gagal', 'Periksa kembali masukan anda');
        //     return redirect()->route('data_admin');
        // }
        $pt = $this->pt->data()->where('nama_pt', $request->pt)->first();

        $data    = [
            'nama_jurnal'=> $request->nama_jurnal,
            'url'        => $request->url,
            'peringkat'  => $request->peringkat,
            'id_pt'      => $pt->id_pt,
            'tgl_buat'   => date('Y-m-d H:i:s'),
        ]; 

        $this->model->data()->insert($data);
        Session::flash('sukses', 'Berhasil menambahkan data');
        return redirect()->route('daftar_jurnal');
    }

    public function update(Request $request, $kode)
    {
        $input = $request->validate([
            'nama_jurnal'=> 'required|max:100',
            'url'        => 'required|max:100',
            'peringkat'  => 'required|max:1',
            'id_pt'      => 'required',
        ]);

        $data    = [
            'nama_jurnal'  => $request->nama_jurnal,
            'url'          => $request->url,
            'peringkat'    => $request->peringkat,
            'id_pt'        => $request->id_pt,
            'tgl_ubah'     => date('Y-m-d H:i:s'),
        ];

        $this->model->data()->where('id_jurnal', $kode)->update($data);
        Session::flash('sukses', 'Berhasil memperbarui data');
        return redirect('/detail_jurnal/'.$kode);
    }

    public function delete($kode)
    {
        $this->model->data()->where('id_jurnal', $kode)->delete();
        Session::flash('sukses', 'Berhasil menghapus data');
        return redirect('/daftar_jurnal');
    }

    public function indexDetail($kode)
    {
        $data       = $this->model->data()
                    ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                    ->where('id_jurnal', $kode)
                    ->first();
        $j_kategori = $this->kategori->data()->where('id_jurnal', $kode)->get();
        $j_publikasi= $this->publikasi->data()->where('id_jurnal', $kode)->get();
        
        // $j_publikasi       = $this->model->data()
        //                     ->leftJoin('t_publikasi_jurnal', 't_publikasi_jurnal.id_jurnal', '=', 't_jurnal.id_jurnal')
        //                     ->where('t_jurnal.id_jurnal', $kode)
        //                     ->get();
        $kategori   = $this->kategori->data()->groupBy('nama_kategori')->get();

        return view('admin.detailJurnal', ['data' => $data, 'j_kategori' => $j_kategori, 'kategori' => $kategori, 'j_publikasi' => $j_publikasi]);
    }

    public function createJK(Request $request, $kode)
    {
        $data = [
            'id_jurnal'     => $kode,
            'nama_kategori' => $request->kategori
        ];
        $data = $this->kategori->data()->insert($data);
        Session::flash('sukses', 'Berhasil menambahkan kategori');
        return redirect('/detail_jurnal/'.$kode);
    }

    public function updateJK(Request $request, $kode)
    {
        $data = [
            'nama_kategori' => $request->kategori
        ];

        $j = $this->kategori->data()->where('id_kategori', $kode)->first();

        $data = $this->kategori->data()->where('id_kategori', $kode)->update($data);

        Session::flash('sukses', 'Berhasil memperbarui kategori');
        return redirect('/detail_jurnal/'.$j->id_jurnal);
    }

    public function deleteJK($kode)
    {
        $j = $this->kategori->data()->where('id_kategori', $kode)->first();

        $this->kategori->data()->where('id_kategori', $kode)->delete();

        Session::flash('sukses', 'Berhasil menghapus kategori');
        return redirect('/detail_jurnal/'.$j->id_jurnal);
    }

    public function createJP(Request $request, $kode)
    {
        $data = [
            'id_jurnal'     => $kode,
            'bulan'         => $request->bulan
        ];
        $data = $this->publikasi->data()->insert($data);
        Session::flash('sukses', 'Berhasil menambahkan jadwal publikasi');
        return redirect('/detail_jurnal/'.$kode);
    }

    public function updateJP(Request $request, $kode)
    {
        $data = [
            'bulan' => $request->bulan
        ];

        $j = $this->publikasi->data()->where('id_publikasi_jurnal', $kode)->first();

        $data = $this->publikasi->data()->where('id_publikasi_jurnal', $kode)->update($data);

        Session::flash('sukses', 'Berhasil memperbarui jadwal publikasi');
        return redirect('/detail_jurnal/'.$j->id_jurnal);
    }

    public function deleteJP($kode)
    {
        $j = $this->publikasi->data()->where('id_publikasi_jurnal', $kode)->first();

        $this->publikasi->data()->where('id_publikasi_jurnal', $kode)->delete();

        Session::flash('sukses', 'Berhasil menghapus jadwal publikasi');
        return redirect('/detail_jurnal/'.$j->id_jurnal);
    }
}
