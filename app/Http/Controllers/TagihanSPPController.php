<?php

namespace App\Http\Controllers;

use App\Models\KasModel;
use App\Models\SiswaModel;
use App\Models\TagihanSPPDetailModel;
use App\Models\TagihanSPPModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TagihanSPPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => '',

        ];

        return view('private.data.tagihan_spp.view')->with($data);
    }

    public function show(Request $r)
    {
        if ($r->ajax()) {
            // Ambil semua siswa aktif
            $result = SiswaModel::with('spp')
                ->where('status_siswa', 'aktif')
                ->get();

            // Hitung nominal SPP per siswa
            $result->transform(function ($siswa) {
                $siswa->nominal_spp = ($siswa->kategori_biaya ?? 0) - ($siswa->pengurangan_biaya ?? 0);

                return $siswa;
            });

            $totalSiswaAktif = $result->count();

            $jumlahSiswaX = $result->where('kelas', 'X')->count();
            $jumlahSiswaXI = $result->where('kelas', 'XI')->count();
            $jumlahSiswaXII = $result->where('kelas', 'XII')->count();

            $totalTagihanBulanIni = $result->sum('nominal_spp');

            // Total tagihan per jurusan
            $tagihanPerJurusan = $result
                ->groupBy('jurusan')
                ->map(function ($row) {
                    return [
                        'jurusan' => $row->first()->jurusan,
                        'total' => $row->sum('nominal_spp'),
                    ];
                })
                ->values();

            return view('private.data.tagihan_spp.show', [
                'result' => $result,
                'totalSiswaAktif' => $totalSiswaAktif,
                'jumlahSiswaX' => $jumlahSiswaX,
                'jumlahSiswaXI' => $jumlahSiswaXI,
                'jumlahSiswaXII' => $jumlahSiswaXII,
                'totalTagihanBulanIni' => $totalTagihanBulanIni,
                'tagihanPerJurusan' => $tagihanPerJurusan,
            ]);
        } else {
            abort(403, 'Akses tidak diperbolehkan');
        }
    }

    public function create_tagihan_spp($id)
    {
        if (! request()->ajax()) {
            abort(403, 'Akses tidak diperbolehkan');
        }

        // Ambil tahun login dari session
        $tahun_login = session('tahun_login', date('Y'));

        // Ambil data siswa berdasarkan ID
        $siswa = SiswaModel::findOrFail($id);

        // Hitung nominal SPP per bulan (jika ada pengurangan biaya)
        $nominal_spp = ($siswa->kategori_biaya ?? 0) - ($siswa->pengurangan_biaya ?? 0);

        // Ambil semua tagihan SPP siswa untuk tahun login
        $tagihan_spp = TagihanSPPModel::where('id_siswa', $id)
            ->where('tahun', $tahun_login)
            ->get();

        // Buat array bulan (1-12)
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Tandai status setiap bulan
        $bulan_status = [];
        foreach ($bulan as $no => $nama) {
            $data_bulan = $tagihan_spp->where('bulan', $no)->first();

            $bulan_status[$no] = [
                'nama' => $nama,
                'lunas' => $data_bulan && strcasecmp($data_bulan->status_bayar, 'lunas') === 0,
                'id_tagihan' => $data_bulan->id_tagihan ?? null,
                'nominal' => $data_bulan->nominal ?? $nominal_spp,
                'status' => $data_bulan->status_bayar ?? 'belum', // fallback
            ];
        }

        $data = [
            'title_form' => 'FORM INPUT DATA SPP',
            'tmp_id_siswa' => $id,
            'siswa' => $siswa,
            'bulan_status' => $bulan_status,
            'nominal_spp' => $nominal_spp,
            'tahun_login' => $tahun_login, // kirim ke view biar jelas
        ];

        return view('private.data.tagihan_spp.formadd', $data);
    }

    public function store_tagihan_spp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:a_siswa,id_siswa',
            'nominal_spp' => 'required|numeric|min:0',
            'tgl_bayar' => 'required|date',
            'metode_bayar' => 'required|string|max:50',
            'bulan' => 'required|array|min:1',
            'status_bayar' => 'required|in:belum,lunas',
            'tahun' => 'required|integer',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'bulan.required' => 'Minimal satu bulan harus dicentang',
            'tgl_bayar.required' => 'Tanggal bayar wajib diisi',
            'tahun.required' => 'Tahun wajib ada',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $siswa = SiswaModel::find($request->id_siswa);

            $total_bayar = 0; // untuk kas
            $bulan_terbayar = [];

            foreach ($request->bulan as $bulan) {
                // Buat atau ambil tagihan SPP untuk tahun login
                $tagihan = TagihanSPPModel::firstOrCreate(
                    [
                        'id_siswa' => $request->id_siswa,
                        'bulan' => $bulan,
                        'tahun' => $request->tahun,
                    ],
                    [
                        'tgl_bayar' => $request->tgl_bayar,
                        'nominal' => $request->nominal_spp,
                        'status_bayar' => 'belum',
                    ]
                );

                // Simpan detail pembayaran
                TagihanSPPDetailModel::create([
                    'id_tagihan' => $tagihan->id_tagihan,
                    'bulan' => $bulan,
                    'tahun' => $request->tahun,
                    'tgl_bayar' => $request->tgl_bayar,
                    'nominal_bayar' => $request->nominal_spp,
                    'metode_bayar' => $request->metode_bayar,
                    'keterangan' => $request->status_bayar === 'lunas'
                        ? 'Pembayaran lunas untuk bulan '.$bulan
                        : ($request->keterangan ?? null),
                ]);

                // Update status bayar di tabel utama
                $tagihan->update([
                    'status_bayar' => $request->status_bayar,
                    'tgl_bayar' => $request->tgl_bayar,
                ]);

                $total_bayar += $request->nominal_spp;
                $bulan_terbayar[] = $bulan;
            }

            // Simpan ke kas masuk 1 record
            KasModel::create([
                'id_pendaftaran' => $siswa->id_pendaftaran,
                'id_siswa' => $siswa->id_siswa,
                'tahun' => $request->tahun,
                'tanggal' => $request->tgl_bayar,
                'kd_kas' => 1, // kas masuk
                'keterangan' => 'SPP Bulan '.implode(', ', $bulan_terbayar).' - '.$siswa->nama_lengkap,
                'jumlah' => $total_bayar,
            ]);

            DB::commit();

            return response()->json([
                'success' => 'Pembayaran SPP berhasil disimpan dan tercatat di kas',
                'myReload' => 'tagihan_sppdata',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Gagal menyimpan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function show_tagihan_spp($id_siswa)
    {
        $tahun_login = session('tahun_login', date('Y'));

        // Ambil data tagihan SPP per bulan + relasi detail
        $result = TagihanSPPModel::with(['detail' => function ($q) {
            $q->orderBy('tgl_bayar', 'asc');
        }])
            ->where('id_siswa', $id_siswa)
            ->where('tahun', $tahun_login) // filter sesuai tahun login
            ->orderBy('bulan', 'asc')
            ->get();

        if (request()->ajax()) {
            return view('private.data.tagihan_spp.show_tagihan_detail', compact('result'));
        }
    }

    public function edit(TagihanSPPModal $tagihanSPPModal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TagihanSPPModal $tagihanSPPModal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (! request()->ajax()) {
            abort(403, 'Tidak dapat diproses');
        }

        DB::beginTransaction();

        try {
            $tagihan = TagihanSPPModel::with('detail', 'siswa')->find($id);

            if (! $tagihan) {
                return response()->json([
                    'error' => 'Tagihan tidak ditemukan atau sudah dihapus',
                ], 404);
            }

            $id_siswa = $tagihan->id_siswa;
            $tgl_bayar = $tagihan->tgl_bayar;

            foreach ($tagihan->detail as $detail) {
                // Ambil kas terkait (per siswa & tanggal)
                $kas = KasModel::where('id_siswa', $id_siswa)
                    ->where('kd_kas', 1)
                    ->where('tanggal', $tgl_bayar)
                    ->first();

                if ($kas) {
                    $bulanSekarang = $kas->bulan ?? []; // pastikan array
                    if (($key = array_search($detail->bulan, $bulanSekarang)) !== false) {
                        // Kurangi jumlah & hapus bulan
                        $kas->jumlah -= $detail->nominal_bayar;
                        unset($bulanSekarang[$key]);
                    }

                    $bulanSekarang = array_values($bulanSekarang);

                    if (count($bulanSekarang) > 0) {
                        $kas->bulan = $bulanSekarang;
                        $kas->keterangan = 'SPP Bulan '.implode(',', $bulanSekarang).' - '.$tagihan->siswa->nama;
                        $kas->save();
                    } else {
                        $kas->delete();
                    }
                }

                $detail->delete();
            }

            // Hapus header jika tidak ada detail tersisa
            if ($tagihan->detail()->count() == 0) {
                $tagihan->delete();
            }

            DB::commit();

            return response()->json([
                'success' => 'Tagihan, detail, dan kas terkait berhasil dihapus/diupdate',
                'myReload' => 'tagihan_sppdata',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Gagal menghapus: '.$e->getMessage(),
            ], 500);
        }
    }
}
