<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index()
    {
        // echo bcrypt('123456');
        if (Auth::user()) {
            $user = Auth::user();

            return redirect()->intended('/main');
        }

        return view('login.view_login');
    }

    public function proses(Request $r)
    {
        $r->validate(
            [
                'email' => 'required',
                'password' => 'required',
                'tahun' => 'required',
            ],
            [
                'email.required' => 'Username tidak boleh kosong',
                'password.required' => 'Password tidak boleh kosong',
                'tahun.required' => 'Tahun tidak boleh kosong',
            ],
        );

        $credentials = $r->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Login berhasil
            $r->session()->put('tahun_login', $r->tahun); // simpan nilai tahun dalam session

            $dtuser = DB::table('users')->where('email', $r->email)->first();

            // $r->session()->put('kode_opd', $dtuser->kd_opd);
            // $r->session()->put('kode_prov', $dtuser->kd_prov);
            // $r->session()->put('kode_kab', $dtuser->kd_kab);
            // return redirect()->intended('cekdata');
            //  return redirect('/');
            return redirect()->intended('login');
        } else {
            // echo "login salah";

            // return back()->withErrors([
            //      'keterangan' => "MAAF USERNAME / PASSWORD ANDA SALAH"
            // ])->onlyInput('email');

            $errors = 'MAAF USERNAME / PASSWORD ANDA SALAH';
            session()->flash('login_error', $errors);

            return redirect()->back()
                ->withErrors($errors);
        }

        // echo $r->email;
        // echo $r->password;
    }

    // public function cekdata()
    // {
    //     Auth::user();
    // }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
