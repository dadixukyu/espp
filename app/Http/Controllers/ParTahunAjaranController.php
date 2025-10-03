<?php

namespace App\Http\Controllers;

use App\Models\ParTahunAjaranModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ParTahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = [
            'title' => '',

        ];

        return view('private.data.par_tahun_ajaran.view')->with($data);

    }

    public function show(string $id)
    {
        if (request()->ajax()) {
            $tahun = Session::get('tahun_login');
            $data = [
                'result' => ParTahunAjaranModel::where('tahun', $tahun)->get(),
            ];

            return view('private.data.par_tahun_ajaran.show', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function create()
    {
        if (request()->ajax()) {
            $tahun = session('tahun_login');
            $data = [
                'title_form' => 'FORM INPUT TAHUN AJARAN',
                'tahun_login' => session('tahun_login') ?? date('Y'),
            ];

            return view('private.data.par_tahun_ajaran.formadd', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'tahun_awal' => 'required|digits:4|integer',
            'tahun_akhir' => 'required|digits:4|integer|gt:tahun_awal',
            'nama_ta' => 'required|string|max:20|unique:par_tahun_ajaran,nama_ta',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'tahun_awal.required' => 'Tahun Awal tidak boleh kosong',
            'tahun_awal.digits' => 'Tahun Awal harus 4 digit',
            'tahun_akhir.required' => 'Tahun Akhir tidak boleh kosong',
            'tahun_akhir.digits' => 'Tahun Akhir harus 4 digit',
            'tahun_akhir.gt' => 'Tahun Akhir harus lebih besar dari Tahun Awal',
            'nama_ta.required' => 'Nama Tahun Ajaran tidak boleh kosong',
            'nama_ta.unique' => 'Nama Tahun Ajaran sudah ada',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = ParTahunAjaranModel::create([
            'tahun_awal' => $r->tahun_awal,
            'tahun_akhir' => $r->tahun_akhir,
            'nama_ta' => $r->nama_ta,
            'status' => $r->status,
            'tahun' => Session::get('tahun_login'),

        ]);

        return response()->json([
            'success' => 'Data Tahun Ajaran berhasil disimpan',
            'myReload' => 'partahundata',
        ]);
    }

    public function edit(string $id)
    {
        if (request()->ajax()) {
            $tahun = session('tahun_login');
            $row = ParTahunAjaranModel::where('id_tahun', $id)->first();
            $data = [
                'id_tahun' => $id,
                'row' => $row,
                'title_form' => 'FORM EDIT TAHUN AJARAN',
                'tahun_login' => session('tahun_login') ?? date('Y'),

            ];

            return view('private.data.par_tahun_ajaran.formedit', $data);
        } else {
            exit('Maaf, request tidak dapat diproses');
        }
    }

    public function update(Request $r, $id)
    {
        // Cari data berdasarkan primary key id_tahun
        $partahunajaran = ParTahunAjaranModel::where('id_tahun', $id)->first();
        if (! $partahunajaran) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Validasi input
        $validator = Validator::make($r->all(), [
            'tahun_awal' => 'required|digits:4|integer',
            'tahun_akhir' => 'required|digits:4|integer|gt:tahun_awal',
            'nama_ta' => 'required|string|max:20|unique:par_tahun_ajaran,nama_ta,'.$id.',id_tahun',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'tahun_awal.required' => 'Tahun Awal tidak boleh kosong',
            'tahun_awal.digits' => 'Tahun Awal harus 4 digit',
            'tahun_akhir.required' => 'Tahun Akhir tidak boleh kosong',
            'tahun_akhir.digits' => 'Tahun Akhir harus 4 digit',
            'tahun_akhir.gt' => 'Tahun Akhir harus lebih besar dari Tahun Awal',
            'nama_ta.required' => 'Nama Tahun Ajaran tidak boleh kosong',
            'nama_ta.unique' => 'Nama Tahun Ajaran sudah ada',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update data
        $partahunajaran->update([
            'tahun_awal' => $r->tahun_awal,
            'tahun_akhir' => $r->tahun_akhir,
            'nama_ta' => $r->nama_ta,
            'status' => $r->status,
            'tahun' => Session::get('tahun_login'),
        ]);

        return response()->json([
            'success' => 'Data Tahun Ajaran berhasil diperbarui',
            'myReload' => 'partahundata',
        ]);
    }

    public function destroy($id)
    {
        if (request()->ajax()) {

            $post = ParTahunAjaranModel::where('id_tahun', $id)->delete();

            if ($post) {
                return response()->json([
                    'success' => 'Data Tahun Ajaran berhasil dihapus',
                    'myReload' => 'partahundata',
                ]);
            }
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }
}
