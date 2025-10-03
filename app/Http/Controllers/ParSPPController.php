<?php

namespace App\Http\Controllers;

use App\Models\ParSPPModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ParSPPController extends Controller
{
    public function index()
    {

        $data = [
            'title' => '',

        ];

        return view('private.data.par_spp.view')->with($data);

    }

    public function show(string $id)
    {
        if (request()->ajax()) {
            // Ambil tahun login dari session, jika belum ada gunakan tahun sekarang
            $tahun = session('tahun_login', date('Y'));

            // Ambil data SPP berdasarkan tahun login
            $data = [
                'result' => ParSPPModel::where('tahun', $tahun)->get(),
            ];

            return view('private.data.par_spp.show', $data);
        } else {
            abort(403, 'Maaf, permintaan ini tidak dapat diproses.');
        }
    }

    public function create()
    {
        if (request()->ajax()) {
            $tahun = session('tahun_login');
            $data = [
                'title_form' => 'FORM INPUT NOMINAL SPP',
                'tahun_login' => session('tahun_login') ?? date('Y'),
            ];

            return view('private.data.par_spp.formadd', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            // 'tahun' => 'required',
            // 'nama_spp'   => 'required',
            'nominal' => 'required',
            'keterangan' => 'nullable|string',
        ], [
            // 'tahun.required' => 'Tahun tidak boleh kosong',
            // 'kode_spp.unique'   => 'Kode SPP sudah ada',
            // 'nama_spp.required' => 'Nama SPP tidak boleh kosong',
            'nominal.required' => 'Nominal tidak boleh kosong',
            'nominal.numeric' => 'Nominal harus berupa angka',
            'nominal.min' => 'Nominal tidak boleh negatif',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json(['errors' => $errors], 422);
        }

        $nominal = preg_replace('/[^0-9]/', '', $r->nominal);

        $post = ParSPPModel::create([
            // 'tahun' => $r->tahun,
            // 'nama_spp'   => $r->nama_spp,
            'nominal' => $nominal,
            'keterangan' => $r->keterangan,
            'tahun' => Session::get('tahun_login'),
        ]);

        return response()->json([
            'success' => 'Data berhasil disimpan',
            'myReload' => 'parsppdata',
        ]);
    }

    public function edit(string $id)
    {
        if (request()->ajax()) {
            $row = ParSPPModel::where('id', $id)->first();
            $data = [
                'id' => $id,
                'row' => $row,
                'title_form' => 'FORM EDIT NOMINAL SPP',
            ];

            return view('private.data.par_spp.formedit', $data);
        } else {
            exit('Maaf, request tidak dapat diproses');
        }
    }

    public function update(Request $r, $id)
    {

        $parspp = ParSPPModel::find($id);
        if (! $parspp) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $validator = Validator::make($r->all(), [
            // 'tahun' => 'required|numeric|digits:4',
            // 'nama_spp'   => 'required',
            'nominal' => 'required',
            'keterangan' => 'nullable|string',
        ], [
            // 'tahun.required' => 'Tahun tidak boleh kosong',
            // 'kode_spp.unique'   => 'Kode SPP sudah ada',
            // 'nama_spp.required' => 'Nama SPP tidak boleh kosong',
            'nominal.required' => 'Nominal tidak boleh kosong',
            'nominal.numeric' => 'Nominal harus berupa angka',
            'nominal.min' => 'Nominal tidak boleh negatif',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $nominal = preg_replace('/[^0-9]/', '', $r->nominal);

        $parspp->update([
            // 'tahun' => $r->tahun,
            'nominal' => $nominal,
            'keterangan' => $r->keterangan,
        ]);

        return response()->json([
            'success' => 'Data berhasil diperbarui',
            'myReload' => 'parsppdata',
        ]);
    }

    public function destroy($id)
    {
        if (request()->ajax()) {

            $post = ParSPPModel::where('id', $id)->delete();

            if ($post) {
                return response()->json([
                    'success' => 'Data SPP berhasil dihapus',
                    'myReload' => 'parsppdata',
                ]);
            }
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }
}
