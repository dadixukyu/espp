<?php

namespace App\Http\Controllers;

use App\Models\ParBiayaModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ParBiayaController extends Controller
{
    public function index()
    {
        {
            $data = [
                'title' => '',
                
            ];
            return view('private.data.par_biaya.view')->with($data);
        }
    }


    public function show(string $id)
    {
        if (request()->ajax()) {
            $data = [
                'result' => ParBiayaModel::all(), // ambil semua baris
            ];
            return view('private.data.par_biaya.show', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }
    
    public function create()
    {
        if (request()->ajax()) {
            $data = [
                'title_form' => 'FORM INPUT BIAYA',
            ];
            return view('private.data.par_biaya.formadd', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    
    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'tahun'   => 'required',
            'nama_biaya'   => 'required',
            'nominal'    => 'required',
            'keterangan' => 'nullable|string',
        ], [
            'tahun.required' => 'Tahun tidak boleh kosong',
            'nama_biaya.required' => 'Nama Biaya tidak boleh kosong',
            'nominal.required'  => 'Nominal tidak boleh kosong',
            'nominal.numeric'   => 'Nominal harus berupa angka',
            'nominal.min'       => 'Nominal tidak boleh negatif',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['errors' => $errors], 422);
        }

   
        $nominal = preg_replace('/[^0-9]/', '', $r->nominal);

        $post = ParBiayaModel::create([
            'tahun'   => $r->tahun,
            'nama_biaya'   => $r->nama_biaya,
            'nominal'    => $nominal,
            'keterangan' => $r->keterangan,
        ]);

        return response()->json([
            'success'  => 'Data berhasil disimpan',
            'myReload' => 'parbiayadata'
        ]);
    }

    public function edit(string $id)
    {
        if (request()->ajax()) {
            $row = ParBiayaModel::where('id_biaya', $id)->first();
            $data = [
                'id_biaya' => $id,
                'row' => $row,
                'title_form' => 'FORM EDIT BIAYA',
                
            ];
            return view('private.data.par_biaya.formedit', $data);
        } else {
            exit('Maaf, request tidak dapat diproses');
        }
    }

    public function update(Request $r, $id)
    {
    
        $parbiaya = ParBiayaModel::where('id_biaya', $id)->first();
        if (!$parbiaya) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $validator = Validator::make($r->all(), [
             'tahun'   => 'required|numeric|digits:4',
              'nama_biaya' => 'required', 
            'nominal'    => 'required',
            'keterangan' => 'nullable|string',
        ], [
            'tahun.required' => 'Tahun tidak boleh kosong',
            'nama_biaya.required' => 'Tahun tidak boleh kosong',
            'nominal.required'  => 'Nominal tidak boleh kosong',
            'nominal.numeric'   => 'Nominal harus berupa angka',
            'nominal.min'       => 'Nominal tidak boleh negatif',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $nominal = preg_replace('/[^0-9]/', '', $r->nominal);

        $parbiaya->update([
            'tahun'   => $r->tahun,
            'nama_biaya'   => $r->nama_biaya,
            'nominal'    => $nominal,
            'keterangan' => $r->keterangan,
        ]);

        return response()->json([
            'success'  => 'Data berhasil diperbarui',
            'myReload' => 'parbiayadata'
        ]);
    }

    public function destroy($id)
    {
        if (request()->ajax()) {

            $post = ParBiayaModel::where('id_biaya', $id)->delete();

            if ($post) {
                return response()->json([
                    'success'  => 'Data Biaya berhasil dihapus',
                    'myReload' => 'parbiayadata'
                ]);
            }
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }
}