<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use DB; 
use Carbon\Carbon; 
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DataAdminM;
use Illuminate\Support\Facades\Mail;
use App\Mail\LupaSandiMail;
use App\Mail\UpdateSandiMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    // public function index()
    // {
    //    return view('auth.forgetPassword');
    // }
    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:t_admin',
        ]);

        $token = Str::random(64);
        $data   = $this->model->data()->where('email', $request->email)->get();

        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
        ]);

        // Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
        //     $message->to($request->email);
        //     $message->subject('Reset Password');
        // });
        Mail::to($request->email)->send(new LupaSandiMail($data[0]->nama_admin, $token));

        return back()->with('message', 'We have e-mailed your password reset link!');
    }
}
