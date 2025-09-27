<?php

namespace App\Http\Controllers;

use App\Models\ParKelasModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ParKelasController extends Controller
{
    public function index()
    {
        {
            $data = [
                'title' => '',
                
            ];
            return view('private.data.par_kelas.view')->with($data);
        }
    }


    public function show(string $id)
    {
        if (request()->ajax()) {
            $data = [
                'result' => ParKelasModel::all(), // ambil semua baris
            ];
            return view('private.data.par_kelas.show', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }
    
    public function create()
    {
        if (request()->ajax()) {
            $data = [
                'title_form' => 'FORM INPUT KELAS',
            ];
            return view('private.data.par_kelas.formadd', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }


    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            
            'kelas' => 'required|string|max:20|:par_kelas,kelas',
            'jurusan' => 'required|string|max:20|:par_kelas,jurusan',
        ], [
            
            'kelas.required'    => 'Kelas tidak boleh kosong',
            // 'kelas.unique'      => 'Kelas sudah ada',
            'jurusan.required'     => 'Jurusan tidak boleh kosong',
            // 'jurusan.unique'      => 'Jurusan sudah ada',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = ParKelasModel::create([
            'kelas' => $r->kelas,
            'jurusan' => $r->jurusan,
           
        ]);

        return response()->json([
            'success'  => 'Data Kelas berhasil disimpan',
            'myReload' => 'parkelasdata'
        ]);
    }

    public function edit(string $id)
    {
        if (request()->ajax()) {
            $row = ParKelasModel::where('id_kelas', $id)->first();
            $data = [
                'id_kelas' => $id,
                'row' => $row,
                'title_form' => 'FORM EDIT KELAS',
                
            ];
            return view('private.data.par_kelas.formedit', $data);
        } else {
            exit('Maaf, request tidak dapat diproses');
        }
    }

    public function update(Request $r, $id)
    {
        // Cari data berdasarkan primary key id_tahun
        $parkelas = ParKelasModel::where('id_kelas', $id)->first();
        if (!$parkelas) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Validasi input
        $validator = Validator::make($r->all(), [
            
            'kelas'   => 'nullable|string|max:20',
            'jurusan' => 'required|string|max:20'. $id . ',id_kelas',
        ], [
            
            'kelas.required'    => 'Kelas tidak boleh kosong',
            // 'kelas.unique'      => 'Kelas sudah ada',
            'jurusan.required'     => 'Jurusan tidak boleh kosong',
            // 'jurusan.unique'      => 'Jurusan sudah ada',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update data
        $parkelas->update([
            'kelas' => $r->kelas,
            'jurusan' => $r->jurusan,
        ]);

        return response()->json([
            'success' => 'Data Kelas berhasil diperbarui',
            'myReload' => 'parkelasdata'
        ]);
    }
    public function destroy($id)
    {
        if (request()->ajax()) {

            $post = ParKelasModel::where('id_kelas', $id)->delete();

            if ($post) {
                return response()->json([
                    'success'  => 'Data Kelas berhasil dihapus',
                    'myReload' => 'parkelasdata'
                ]);
            }
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }
}