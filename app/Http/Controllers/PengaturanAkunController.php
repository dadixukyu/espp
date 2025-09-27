<?php

namespace App\Http\Controllers;

use App\Models\PengaturanAkunModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengaturanAkunController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'PENGATURAN AKUN',
        ];
        return view('private.data.pengaturan_akun.view')->with($data);
    }

    public function updatepassword(Request $r, $id)
    {
        if (request()->ajax()) {
            $validator = Validator::make($r->all(), [
                'password_old' => 'required',
                'password_new' => 'required|min:6|confirmed',
            ], [
                'password_old.required' => 'Password Lama tidak boleh kosong.',
                'password_new.required' => 'Password Baru tidak boleh kosong.',
                'password_new.min' => 'Password Minimal 6 Karakter',
                'password_new.confirmed' => 'Password baru dan confirm tidak sama.',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['errors' => $errors], 422);
            } else {
                $userdata = PengaturanAkunModel::find($id);

                // Mengecek apakah password lama yang dimasukkan benar atau tidak
                if (Auth::attempt(['email' => $userdata->email, 'password' => $r->password_old])) {
                    PengaturanAkunModel::where('id', $id)->update([
                        'password' => bcrypt($r->password_new),
                    ]);
                    return response()->json(['success' => 'Password Berhasil diubah', 'route' => route('logout'), 'myReload' => 'ReloadPassword']);
                } else {
                    return response()->json(['errors' => ['password_old' => ['Password Lama Salah']]], 422);
                }
            }
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function updateemail(Request $r)
    {
        if (request()->ajax()) {
            $validator = Validator::make($r->all(), [
                'email' => 'required|unique:users,email',
            ], [
                'email.required' => 'Username tidak boleh kosong.',
                'email.unique' => 'Username Sudah di gunakan.'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['errors' => $errors], 422);
            } else {
                PengaturanAkunModel::where('id', $r->id)->update([
                    'email'  => $r->email,
                ]);
                return response()->json(['success' => 'Username Berhasil diubah', 'email' => $r->email, 'myReload' => 'no']);
            }
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }
}