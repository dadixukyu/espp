<?php

namespace App\Http\Controllers;

use App\Models\ParBiayaModel;
use App\Models\PendaftaranModel;
use App\Models\TagihanLainDetailModel;
use App\Models\TagihanLainModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TagihanLainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Data Tagihan Lain',
        ];

        return view('private.data.tagihan_lain.view', $data);
    }

    /**
     * Show: Menampilkan detail tagihan & daftar pembayaran
     */
    public function show(Request $r)
    {
        if ($r->ajax()) {

            $tahun_login = session('tahun_login', date('Y'));

            // Ambil semua data pendaftaran sesuai tahun_login
            $pendaftaran = PendaftaranModel::with(['spp', 'tagihanLain.detail'])
                ->where('tahun', $tahun_login) // pastikan ada field 'tahun' di tabel pendaftaran
                ->get();

            // Hitung status keseluruhan per siswa
            foreach ($pendaftaran as $siswa) {
                $tagihanAll = $siswa->tagihanLain;

                if ($tagihanAll->isEmpty()) {
                    $siswa->status_siswa = 'Belum Bayar';
                } else {
                    $allLunas = $tagihanAll->every(fn ($t) => strtolower($t->status) === 'lunas');
                    $allBelumBayar = $tagihanAll->every(fn ($t) => strtolower($t->status) === 'belum_bayar');

                    if ($allLunas) {
                        $siswa->status_siswa = 'Lunas';
                    } elseif ($allBelumBayar) {
                        $siswa->status_siswa = 'Belum Bayar';
                    } else {
                        $siswa->status_siswa = 'Cicilan';
                    }
                }
            }

            // Hitung total pendaftar
            $jumlahPendaftar = $pendaftaran->count();

            // Hitung status tagihan per siswa
            $statusSummary = [
                'lunas' => $pendaftaran->where('status_siswa', 'Lunas')->count(),
                'cicilan' => $pendaftaran->where('status_siswa', 'Cicilan')->count(),
                'belum_bayar' => $pendaftaran->where('status_siswa', 'Belum Bayar')->count(),
            ];

            $data = [
                'result' => $pendaftaran,
                'jumlahPendaftar' => $jumlahPendaftar,
                'jumlahLunas' => $statusSummary['lunas'],
                'jumlahCicil' => $statusSummary['cicilan'],
                'jumlahBelumBayar' => $statusSummary['belum_bayar'],
                'tahun_login' => $tahun_login,
            ];

            return view('private.data.tagihan_lain.show', $data);

        } else {
            abort(403, 'Akses tidak diperbolehkan');
        }
    }

    public function create($id_pendaftaran)
    {
        $pendaftaran = PendaftaranModel::findOrFail($id_pendaftaran);
        $siswa = $pendaftaran;

        // Ambil daftar parameter biaya
        $parBiaya = ParBiayaModel::all();

        foreach ($parBiaya as $item) {
            $tagihan = TagihanLainModel::where('id_pendaftaran', $id_pendaftaran)
                ->where('id_biaya', $item->id_biaya)
                ->with('detail') // load relasi pembayaran
                ->first();

            if ($tagihan) {
                // Hitung sudah bayar
                $sudahBayar = (float) ($tagihan->tagihan - $tagihan->sisa_tagihan);

                $item->sudah_bayar = $sudahBayar;
                $item->sisa = (float) $tagihan->sisa_tagihan;

                // Tentukan status otomatis
                if ($tagihan->sisa_tagihan <= 0) {
                    $item->status = 'lunas';
                } elseif ($sudahBayar > 0) {
                    $item->status = 'cicil';
                } else {
                    $item->status = 'belum_bayar';
                }

                // Opsional: jika ingin kirim juga data riwayat cicilan ke view
                $item->riwayat = $tagihan->detail;
            } else {
                // Jika belum ada tagihan, set default
                $item->sudah_bayar = 0;
                $item->sisa = (float) ($item->nominal ?? 0);
                $item->status = 'belum_bayar';
                $item->riwayat = collect(); // kosong
            }
        }

        return view('private.data.tagihan_lain.formadd', [
            'pendaftaran' => $pendaftaran,
            'siswa' => $siswa,
            'parBiaya' => $parBiaya,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pendaftaran' => 'required|exists:a_pendaftaran,id_pendaftaran',
            'metode_bayar' => 'required|string|max:50',
            'tagihan' => 'required|array',
            'keterangan' => 'required|string|max:255',
            'tgl_bayar' => 'required|date',
        ], [
            'id_pendaftaran.required' => 'Data siswa tidak ditemukan',
            'metode_bayar.required' => 'Metode bayar wajib dipilih',
            'tagihan.required' => 'Minimal satu tagihan harus diisi',
            'keterangan.required' => 'Keterangan Harus diisi',
            'tgl_bayar.required' => 'Tanggal Bayar Harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $keterangan = $request->keterangan;
            $tglBayar = $request->tgl_bayar;

            foreach ($request->tagihan as $idBiaya => $nominalInput) {
                $nominal = (int) preg_replace('/\D/', '', (string) $nominalInput);
                if ($nominal <= 0) {
                    continue;
                }

                $parBiaya = ParBiayaModel::find($idBiaya);
                if (! $parBiaya) {
                    continue;
                }

                $fullAmount = (float) $parBiaya->nominal;

                // Hitung total sudah bayar sebelumnya
                $sudahBayar = TagihanLainDetailModel::whereHas('tagihan', function ($q) use ($request, $idBiaya) {
                    $q->where('id_pendaftaran', $request->id_pendaftaran)
                        ->where('id_biaya', $idBiaya);
                })->sum('nominal_bayar');

                $sisa = max(0, $fullAmount - $sudahBayar);

                // Cek apakah input melebihi sisa
                if ($nominal > $sisa) {
                    return response()->json([
                        'errors' => [
                            'tagihan' => ["Pembayaran untuk '{$parBiaya->nama_biaya}' tidak boleh melebihi sisa tagihan Rp ".number_format($sisa, 0, ',', '.')],
                        ],
                    ], 422);
                }

                $totalBayar = $sudahBayar + $nominal;

                $status = $totalBayar >= $fullAmount ? 'Lunas' : ($totalBayar > 0 ? 'Cicilan' : 'Belum Bayar');

                $tagihan = TagihanLainModel::updateOrCreate(
                    [
                        'id_pendaftaran' => $request->id_pendaftaran,
                        'id_biaya' => $idBiaya,
                    ],
                    [
                        'tagihan' => $fullAmount,
                        'sisa_tagihan' => $fullAmount - $totalBayar,
                        'status' => $status,
                    ]
                );

                $tagihan->detail()->create([
                    'tgl_bayar' => $tglBayar,
                    'nominal_bayar' => $nominal,
                    'metode_bayar' => $request->metode_bayar,
                    'keterangan' => $keterangan,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => 'Data Tagihan berhasil disimpan',
                'myReload' => 'tagihanlaindata',
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'error' => 'Terjadi kesalahan: '.$th->getMessage(),
            ], 500);
        }
    }

    public function show_detail($id_pendaftaran)
    {
        $result = TagihanLainModel::with(['biaya', 'detail'])
            ->where('id_pendaftaran', $id_pendaftaran)
            ->get();

        return view('private.data.tagihan_lain.show_tagihan_detail', compact('result'));
    }

    public function destroy($id)
    {
        if (! request()->ajax()) {
            abort(403, 'Tidak dapat diproses');
        }

        DB::beginTransaction();
        try {
            $detail = TagihanLainDetailModel::with('tagihan')->find($id);

            if (! $detail) {
                return response()->json([
                    'error' => 'Data pembayaran tidak ditemukan atau sudah dihapus',
                ], 404);
            }

            $tagihan = $detail->tagihan;
            $detail->delete();

            if ($tagihan) {
                $totalBayar = $tagihan->detail()->sum('nominal_bayar');
                $fullAmount = $tagihan->tagihan;
                $sisa = max(0, $fullAmount - $totalBayar);

                $status = $totalBayar >= $fullAmount ? 'Lunas' :
                        ($totalBayar > 0 ? 'Cicilan' : 'Belum Bayar');

                $tagihan->update([
                    'sisa_tagihan' => $sisa,
                    'status' => $status,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => 'Pembayaran berhasil dihapus dan tagihan diperbarui',
                'myReload' => 'tagihanlaindata',
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus: '.$th->getMessage(),
            ], 500);
        }
    }

    public function edit(TagihanLainModel $tagihanLainModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TagihanLainModel $tagihanLainModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
}
