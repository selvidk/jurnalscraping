<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKategoriM;
use App\Models\IndexKategoriM;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DataKategoriC extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->model = new DataPtM();
        $this->model = new DataKategoriM();
        $this->iKategori= new IndexKategoriM();
    }

    public function index()
    {
        $data = DB::table('t_kategori')
                ->select(DB::raw('count(*) as total, nama_kategori'))
                // ->join('t_pt', 't_pt.id_pt', '=', 't_jurnal.id_pt')
                // ->groupBy('id_jurnal')
                ->groupBy('nama_kategori')
                ->get();

        return view('admin.daftarKategori', ['data' => $data]);
    }

    public function create(Request $request)
    {
        $input = $request->validate([
            'nama_kategori' => 'required|max:20',
        ]);

        // if ($input->fails()) {
        //     Session::flash('gagal', 'Periksa kembali masukan anda');
        //     return redirect()->route('data_admin');
        // }

        $data    = [
            'nama_kategori'      => $request->nama_kategori,
        ]; 

        $cek = $this->model->data()->where('nama_kategori', $request->nama_kategori)->count();
        if ($cek != 0) {
            Session::flash('gagal', 'Data sudah ada');
        } else {
            $this->model->data()->insert($data);
            Session::flash('sukses', 'Berhasil menambahkan data');
        }
        return redirect()->route('daftar_kategori');
    }

    public function update(Request $request, $kode)
    {
        $input = $request->validate([
            'nama_kategori' => 'required|max:20',
        ]);

        $data    = [
            'nama_kategori' => $request->nama_kategori,
        ]; 

        $this->model->data()->where('nama_kategori', $kode)->update($data);
        
        $this->iKategori->data()->where('nama_asing', $kode)->orWhere('nama_indonesia', $kode)->update(['nama_indonesia' => $request->nama_kategori]);

        Session::flash('sukses', 'Berhasil memperbarui data.');

        // if ($getT[0]->total == 1) {
        //     $cek = $this->model->data()->where('nama_kategori', $kode)->first();
        //     $cek->id_jurnal == NULL ? $total = 0 : $total = $getT[0]->total;
        //     Session::flash('sukses', 'Berhasil memperbarui data untuk '.$total.' jurnal');
        // } else {
        //     $total = $getT[0]->total;
        //     Session::flash('sukses', 'Berhasil memperbarui data untuk '.$total.' jurnal');
        // }
        return redirect()->route('daftar_kategori');
    }

    public function delete($kode, $total)
    {
        if ($total == 0) {
            $this->model->data()->where('nama_kategori', $kode)->delete();
            Session::flash('sukses', 'Berhasil menghapus data');
        } else {
            Session::flash('gagal', 'Tidak dapat menghapus data dengan total jurnal >= 1');
        }
        
        return redirect()->route('daftar_kategori');
    }
}
