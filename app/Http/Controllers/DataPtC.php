<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPtM;
use App\Models\DataKategoriM;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DataPtC extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->model = new DataPtM();
    }

    public function index()
    {
        $data = $this->model->data()
                ->groupBy('nama_pt')
                ->get();

        return view('admin.daftarPT', ['data' => $data]);
    }

    public function create(Request $request)
    {
        $input = $request->validate([
            'nama_pt' => 'required|max:70',
            'alamat'  => 'required|max:255',
        ]);

        $data    = [
            'nama_pt' => $request->nama_pt,
            'alamat'  => $request->alamat,
        ]; 

        $cek = $this->model->data()->where('nama_pt', $request->nama_pt)->count();

        if ($cek == 0) {
            Session::flash('gagal', 'Data sudah ada');
        } else {
            $this->model->data()->insert($data);
            Session::flash('sukses', 'Berhasil menambahkan data');
        }
        return redirect()->route('daftar_pt');
    }

    public function update(Request $request, $kode)
    {
        $input = $request->validate([
            'nama_pt' => 'required|max:70',
            'alamat'  => 'required|max:255',
        ]);

        $data    = [
            'nama_pt' => $request->nama_pt,
            'alamat'  => $request->alamat,
        ]; 

        $this->model->data()->where('id_pt', $kode)->update($data);
        Session::flash('sukses', 'Berhasil memperbarui data');

        return redirect()->route('daftar_pt');
    }

    public function delete($kode)
    {
        $this->model->data()->where('id_pt', $kode)->delete();
        Session::flash('sukses', 'Berhasil menghapus data');
        return redirect()->route('daftar_pt');
    }
}
