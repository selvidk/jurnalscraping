<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataAdminM;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class DataAdminC extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->model = new DataAdminM();
    }

    public function index()
    {
        try {
            $data = $this->model->data()->orderBy('tgl_buat', 'asc')->get();
        } catch (\Throwable $th) {
            //throw $th;
        }
        return view('admin.dataAdmin', ['data' => $data]);
    }

    public function create(Request $request)
    {
        $input = $request->validate([
            'email'      => 'required|email|unique:t_admin,email|max:40',
            'nama_admin' => 'required|max:25',
            'level'      => 'required',
        ]);

        $data    = [
            'email'      => $request->email,
            'nama_admin' => $request->nama_admin,
            'level'      => $request->level,
            'password'   => Hash::make($request->email),
            'tgl_buat'   => date('Y-m-d H:i:s'),
        ]; 

        try {
            $this->model->data()->insert($data);
            return redirect()->route('data_user')->with('sukses', 'Berhasil menambahkan data.');
        } catch (\Throwable $th) {
            return redirect()->route('data_user')->with('error', $th);
        }
        
    }

    public function update(Request $request, $kode)
    {
        $input = $request->validate([
            'email'      => 'required|email|max:40',
            'nama_admin' => 'required|max:25',
            'level'      => 'required',
        ]);

        $data    = [
            'email'      => $request->email,
            'nama_admin' => $request->nama_admin,
            'level'      => $request->level,
            'tgl_ubah'   => date('Y-m-d H:i:s'),
        ];

        try {
            $this->model->data()->where('id', $kode)->update($data);
            return redirect()->route('data_user')->with('sukses', 'Berhasil memperbarui data.');
        } catch (\Illuminate\Database\QueryException $ex) {
            $errorCode = $ex->errorInfo[1];
            if($errorCode == 1062){
                $request->validate([
                    'email'      => 'required|email|unique:t_admin,email|max:40'
                ]);
            }
            return redirect()->route('data_user');
        }
    }

    public function delete($kode)
    {
        try {
            $query = $this->model->data()->where('id', $kode)->delete();
            return redirect()->route('data_user')->with('sukses', 'Berhasil menghapus data.');
        } catch (Exception $e) {
            return redirect()->route('data_user')->with('error', $e);
        }
        
    }
}
