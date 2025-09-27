<?php

namespace App\Http\Controllers;

use App\Models\UserDataModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = [
            'title' => 'DATA USER',
        ];

        return view('private.data.user.view')->with($data);
    }

    public function show()
    {
        if (request()->ajax()) {
            $data = [
                'result' => DB::table('users as u')
                    ->select('u.*') // ambil semua kolom
                    ->orderBy('u.id', 'asc') // urutkan biar rapi
                    ->get(),
            ];

            return view('private.data.user.show', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function create()
    {
        if (request()->ajax()) {
            $data = [
                'title_form' => 'FORM INPUT DATA BARU',
                'result' => null, // tidak ambil provinsi
                // 'opds' => null,   // tidak ambil OPD
            ];

            return view('private.data.user.formadd', $data);

        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'level' => 'required',
        ], [
            'email.required' => 'Username Tidak Boleh Kosong',
            'email.unique' => 'Username Sudah Ada',
            'password.required' => 'Password Tidak Boleh Kosong',
            'level.required' => 'Level Tidak Boleh Kosong',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = UserDataModel::create([
            'email' => $r->email,
            'password' => bcrypt($r->password),
            'level' => $r->level,

        ]);

        return response()->json([
            'success' => 'Data berhasil disimpan',
            'myReload' => 'userdata',
        ]);
    }

    public function destroy($id)
    {
        if (request()->ajax()) {

            $post = UserDataModel::where('id', $id)->delete();

            if ($post) {
                return response()->json([
                    'success' => 'Data berhasil dihapus',
                    'myReload' => 'userdata',
                ]);
            }
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function username($id)
    {
        if (request()->ajax()) {
            $row = UserDataModel::where('id', $id)->first();
            $data = [
                'id' => $id,
                'row' => $row,
                'title_form' => 'FORM EDIT DATA USERNAME',
            ];

            return view('private.data.user.formeditusername', $data);
        } else {
            exit('Maaf, request tidak dapat diproses');
        }
    }

    public function resetpassword($id)
    {
        if (request()->ajax()) {
            $row = UserDataModel::where('id', $id)->first();
            $data = [
                'id' => $id,
                'row' => $row,
                'title_form' => 'RESET PASSWORD',
            ];

            return view('private.data.user.formresetpassword', $data);
        } else {
            exit('Maaf, request tidak dapat diproses');
        }
    }

    public function updateusername(Request $r, $id)
    {
        $validator = Validator::make($r->all(), [
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    $isUnique = UserDataModel::where('email', $value)
                        ->count() === 0;
                    if (! $isUnique) {
                        $fail('Username sudah digunakan');
                    }
                },
            ],
        ], [
            'email.required' => 'Username tidak boleh kosong.',
            'email.unique' => 'Username sudah digunakan.',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json(['errors' => $errors], 422);
        } else {
            UserDataModel::where('id', $id)->update([
                'email' => $r->email,
            ]);

            return response()->json([
                'success' => 'Username Berhasil diupdate',
                'myReload' => 'userdata',
            ]);
        }
    }

    public function updatepassword(Request $r, $id)
    {

        if (request()->ajax()) {
            $post = UserDataModel::where('id', $id)->update([
                'password' => bcrypt('123456'),
            ]);

            return response()->json([
                'success' => 'Data berhasil dihapus',
                'myReload' => 'userdata',
            ]);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }

        // $validator = Validator::make($r->all(), [
        //     'password' => 'required|min:6|confirmed',
        // ], [
        //     'password.required' => 'Password tidak boleh kosong.',
        //     'password.min' => 'Password Minimal 6 Karakter.',
        //     'password.confirmed' => 'Ulangi Password tidak sama.',
        // ]);

        // if ($validator->fails()) {
        //     $errors = $validator->errors();
        //     return response()->json(['errors' => $errors], 422);
        // } else {
        //     UserDataModel::where('id', $id)->update([
        //         'password' => bcrypt($r->password),
        //     ]);
        //     return response()->json(['success' => 'Data berhasil diupdate']);
        // }
    }
}
