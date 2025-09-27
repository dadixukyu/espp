<?php

namespace App\Http\Controllers;

use App\Models\KasModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => '',

        ];

        return view('private.data.kas.view')->with($data);
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            // Ambil tahun login dari session, fallback ke tahun sekarang
            $tahunLogin = Session::get('tahun_login', date('Y'));

            // Ambil data kas sesuai tahun login, urut dari tanggal terbaru
            $kas = KasModel::where('tahun', $tahunLogin)
                ->orderBy('tanggal', 'desc')
                ->get();

            // Hitung total masuk dan keluar berdasarkan kd_kas
            $totalMasuk = $kas->where('kd_kas', 1)->sum('jumlah');
            $totalKeluar = $kas->where('kd_kas', 2)->sum('jumlah');
            $saldo = $totalMasuk - $totalKeluar;

            $data = [
                'result' => $kas,
                'totalMasuk' => $totalMasuk,
                'totalKeluar' => $totalKeluar,
                'saldo' => $saldo,
                'tahun_login' => $tahunLogin, // optional, bisa ditampilkan di view
            ];

            return view('private.data.kas.show', $data);

        } else {
            abort(403, 'Akses tidak diperbolehkan');
        }
    }

    public function create()
    {
        if (request()->ajax()) {

            // Ambil tahun login
            $tahun = session('tahun_login');

            // Siapkan opsi kd_kas
            $opsiKas = [
                ['id' => '1', 'nama' => 'Kas Masuk'],
                ['id' => '2', 'nama' => 'Kas Keluar'],
            ];

            $data = [
                'title_form' => 'FORM INPUT KAS',
                'opsiKas' => $opsiKas,
                'tahun_login' => $tahun, // kirim ke view
            ];

            return view('private.data.kas.formadd', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function store(Request $request)
    {
        if (! $request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permintaan tidak valid!',
            ], 400);
        }

        // Ubah format Rp dan koma menjadi angka float
        $jumlah = str_replace(['Rp', '.', ' '], '', $request->jumlah); // hapus Rp, titik, spasi
        $jumlah = str_replace(',', '.', $jumlah); // ganti koma dengan titik

        $request->merge([
            'jumlah' => $jumlah,
        ]);

        // VALIDASI MENGGUNAKAN VALIDATOR
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'kd_kas' => 'required|in:1,2', // 1 = Kas Masuk, 2 = Kas Keluar
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
        ], [
            'tanggal.required' => 'Tanggal wajib dipilih',
            'tanggal.date' => 'Format tanggal tidak valid',
            'kd_kas.required' => 'Jenis Kas wajib dipilih',
            'kd_kas.in' => 'Jenis Kas tidak valid',
            'keterangan.required' => 'Keterangan wajib diisi',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                KasModel::create([
                    'tanggal' => $request->tanggal,
                    'kd_kas' => $request->kd_kas,
                    'keterangan' => $request->keterangan,
                    'jumlah' => $request->jumlah,
                    'tahun' => Session::get('tahun_login'),
                ]);
            });

            return response()->json([
                'success' => 'Data Kas berhasil disimpan',
                'myReload' => 'kasdata',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            // Cari data kas berdasarkan id
            $kas = KasModel::find($id);

            if (! $kas) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data kas tidak ditemukan!',
                ], 404);
            }

            $data = [
                'title_form' => 'FORM EDIT DATA KAS',
                'kas' => $kas,
            ];

            // Load view form edit
            return view('private.data.kas.formedit', $data);
        }

        abort(403, 'Akses tidak diperbolehkan');
    }

    public function update(Request $request, $id)
    {
        if (! $request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permintaan tidak valid!',
            ], 400);
        }

        // Cari data kas
        $kas = KasModel::find($id);
        if (! $kas) {
            return response()->json([
                'error' => 'Data kas tidak ditemukan',
            ], 404);
        }

        // --- Ubah format Rp & koma jadi angka float ---
        $jumlah = str_replace(['Rp', '.', ' '], '', $request->jumlah); // hapus Rp, titik, spasi
        $jumlah = str_replace(',', '.', $jumlah); // ganti koma jadi titik

        $request->merge([
            'jumlah' => $jumlah,
        ]);

        // --- VALIDASI ---
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'kd_kas' => 'required|in:1,2',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
        ], [
            'tanggal.required' => 'Tanggal wajib dipilih',
            'tanggal.date' => 'Format tanggal tidak valid',
            'kd_kas.required' => 'Jenis Kas wajib dipilih',
            'kd_kas.in' => 'Jenis Kas tidak valid',
            'keterangan.required' => 'Keterangan wajib diisi',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::transaction(function () use ($kas, $request) {
                $kas->update([
                    'tanggal' => $request->tanggal,
                    'kd_kas' => $request->kd_kas,
                    'keterangan' => $request->keterangan,
                    'jumlah' => $request->jumlah,
                ]);
            });

            return response()->json([
                'success' => 'Data Kas berhasil diperbarui',
                'myReload' => 'kasdata',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat memperbarui data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        if (! $request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permintaan tidak valid!',
            ], 400);
        }

        try {
            $kas = KasModel::find($id);

            if (! $kas) {
                return response()->json([
                    'error' => 'Data Kas tidak ditemukan',
                ], 404);
            }

            DB::transaction(function () use ($kas) {
                $kas->delete();
            });

            return response()->json([
                'success' => 'Data Kas berhasil dihapus',
                'myReload' => 'kasdata',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data: '.$e->getMessage(),
            ], 500);
        }
    }
}
