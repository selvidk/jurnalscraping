<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataAdminM;
use Illuminate\Support\Facades\Mail;
use App\Mail\LupaSandiMail;
use App\Mail\UpdateSandiMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AkunC extends Controller
{
    public function __construct() {
        $this->model = new DataAdminM();
    }

    public function gotoReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $data   = $this->model->data()->where('email', $request->email)->get();

        if (count($data) == 0) {
            Session::flash('gagal', 'Email tidak ada dalam daftar');
            return redirect()->back();
        } else {
            // $token = Str::random(64);
            $email    = $data[0]->email;
            $password = $data[0]->password;
            $link     = route('verif_token', ['email' => $email, 'token' => $password]);
            // return $email.$password;
            Mail::to($request->email)->send(new LupaSandiMail($data[0]->nama_admin, $link));

            Session::flash('sukses', 'Periksa email Anda untuk merubah password. Periksa pada SPAM jika tidak terdapat di kotak masuk');
            return redirect('/login');
        }
    }

    public function verifyToken()
    {
        if (empty(request('email')) || empty(request('token'))) {
            return redirect('/forget');
        }
        $email    = request('email');
        $password = request('token');

        $data     = $this->model->data()->where('email', $email)->get();
        if (count($data) == 0) {
            return 'tidak valid';
        } else {
            if ($data[0]->password == $password) { 
                return view('admin.resetPass', ['email' => $email]);
            } else {
                return 'Tidak Valid';
            }
        }
    }

    public function resetPass(Request $request, $email)
    {
        $password   = $request->passBaru;
        $konfirmasi = $request->konfPass;
        $hash       = Hash::make($password);
        $link       = '';

        $data   = $this->model->data()->where('email', $email)->get();
        if (count($data) == 0) {
            return 'tidak valid';
        } else {
            if ($password == $konfirmasi) {
                $this->model->data()->where(['email' => $email])->update(['password' => $hash]);

                Mail::to($email)->send(new UpdateSandiMail($data[0]->nama_admin, empty(Auth::user()->email) ? $status = 0 : $status = 2));
    
                Session::flash('sukses', 'Anda berhasil mengatur ulang password');
                if (!empty(Auth::user()->email)) {
                    return redirect('/profil_admin');
                }
                return redirect()->route('login');
            } else {
                Session::flash('gagal', 'Password tidak sama');
                return redirect()->back();
            }
        }
        Session::flash('gagal', 'Email Anda tidak ada pada daftar');
        return redirect('/login');
    }
}