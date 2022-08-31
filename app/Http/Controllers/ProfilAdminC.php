<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataAdminM;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProfilAdminC extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->model = new DataAdminM();
    }

    public function index()
    {
        $data = $this->model->data()->where('email', Auth::user()->email)->get();

        return view('admin.profil', ['data' => $data]);
    }


    public function updateData(Request $request)
    {
        $input = $request->validate([
            'email'      => 'required|email|max:40',
            'nama_admin' => 'required|max:25',
        ]);

        $data    = [
            'email'      => $request->email,
            'nama_admin' => $request->nama_admin,
            'tgl_ubah'   => date('Y-m-d H:i:s'),
        ];

        try {
            $this->model->data()->where('email', auth()->user()->email)->update($data);
            return redirect()->route('profil')->with('sukses', 'Berhasil memperbarui data.');
        } catch (\Illuminate\Database\QueryException $ex) {
            $errorCode = $ex->errorInfo[1];
            if($errorCode == 1062){
                $request->validate([
                    'email'      => 'required|email|unique:t_admin,email|max:40'
                ]);
            }
            return redirect()->route('profil');
        }
    }

    public function updatePass(Request $request)
    {
        $input = $request->validate([
            'password'            => 'required',
            'password_baru'       => ['required', Rules\Password::defaults()],
            'konfirmasi_password' => ['same:password_baru'],
        ]);

        if (Hash::check($request->password,  auth()->user()->password)) {
            $data    = [
                'password'   => Hash::make($request->passBaru),
                'tgl_ubah'   => date('Y-m-d H:i:s'),
            ];

            $this->model->data()->where('email', auth()->user()->email)->update($data);
            return redirect()->route('profil')->with('sukses', 'Berhasil merubah password.');
        }

        return redirect()->route('profil')->with('error', 'password saat ini tidak sama.');
    }

    public function delete($email)
    {
        $this->model->data()->where('email', $email)->delete();
        return redirect()->route('data_admin');
    }
}
