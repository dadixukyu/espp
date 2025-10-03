<?php

namespace App\Http\Controllers;

use App\Models\KasModel;
use App\Models\ParBiayaModel;
use App\Models\PendaftaranModel;
use App\Models\TagihanLainDetailModel;
use App\Models\TagihanLainModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
    public function show(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Akses tidak diperbolehkan');
        }

        // Ambil tahun login dari session
        $tahun_login = session('tahun_login', date('Y'));

        // Ambil semua pendaftaran siswa sesuai tahun login
        $pendaftaran = PendaftaranModel::with([
            'spp',
            'tagihanLain' => fn ($q) => $q->where('tahun', $tahun_login)->with('detail'),
        ])->where('tahun', $tahun_login)->get();

        // Ambil semua ParBiaya sesuai tahun login
        $parBiaya = ParBiayaModel::where('tahun', $tahun_login)->get()->keyBy('id_biaya');

        // Hitung status siswa dan total bayar
        $pendaftaran->transform(function ($siswa) use ($parBiaya) {
            $totalTagihan = $parBiaya->sum(fn ($p) => (float) $p->nominal);

            $totalBayar = $siswa->tagihanLain->sum(function ($tagihan) {
                return $tagihan->detail->sum('nominal_bayar');
            });

            $siswa->status_siswa = match (true) {
                $totalBayar == 0 => 'Belum Bayar',
                $totalBayar < $totalTagihan => 'Cicilan',
                default => 'Lunas',
            };

            $siswa->totalTagihan = $totalTagihan;
            $siswa->totalBayar = $totalBayar;
            $siswa->totalSisa = max(0, $totalTagihan - $totalBayar);

            return $siswa;
        });

        // Summary status siswa
        $statusSummary = [
            'lunas' => $pendaftaran->where('status_siswa', 'Lunas')->count(),
            'cicilan' => $pendaftaran->where('status_siswa', 'Cicilan')->count(),
            'belum_bayar' => $pendaftaran->where('status_siswa', 'Belum Bayar')->count(),
        ];

        $data = [
            'result' => $pendaftaran,
            'jumlahPendaftar' => $pendaftaran->count(),
            'jumlahLunas' => $statusSummary['lunas'],
            'jumlahCicil' => $statusSummary['cicilan'],
            'jumlahBelumBayar' => $statusSummary['belum_bayar'],
            'tahun_login' => $tahun_login,
        ];

        return view('private.data.tagihan_lain.show', $data);
    }

    public function create($id_pendaftaran)
    {
        $pendaftaran = PendaftaranModel::findOrFail($id_pendaftaran);
        $siswa = $pendaftaran;

        // Ambil tahun login dari session
        $tahun = session('tahun_login');

        // Ambil daftar parameter biaya sesuai tahun login
        $parBiaya = ParBiayaModel::where('tahun', $tahun)->get();

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

                // Opsional: kirim juga data riwayat cicilan ke view
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
            $tahun = date('Y', strtotime($tglBayar));
            $totalKas = 0;

            $siswa = PendaftaranModel::findOrFail($request->id_pendaftaran);
            $namaBiayaBayar = [];

            // --- Generate kode transaksi unik ---
            $kodeTransaksi = 'TRX'.date('YmdHis').rand(100, 999);

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

                $sudahBayar = TagihanLainDetailModel::whereHas('tagihan', function ($q) use ($request, $idBiaya) {
                    $q->where('id_pendaftaran', $request->id_pendaftaran)
                        ->where('id_biaya', $idBiaya);
                })->sum('nominal_bayar');

                $sisa = max(0, $fullAmount - $sudahBayar);

                if ($nominal > $sisa) {
                    return response()->json([
                        'errors' => [
                            'tagihan' => ["Pembayaran untuk '{$parBiaya->nama_biaya}' tidak boleh melebihi sisa tagihan Rp ".number_format($sisa, 0, ',', '.')],
                        ],
                    ], 422);
                }

                $totalBayarBiaya = $sudahBayar + $nominal;
                $statusBiaya = $totalBayarBiaya >= $fullAmount ? 'Lunas' : ($totalBayarBiaya > 0 ? 'Cicilan' : 'Belum Bayar');

                $tagihan = TagihanLainModel::updateOrCreate(
                    [
                        'id_pendaftaran' => $request->id_pendaftaran,
                        'id_biaya' => $idBiaya,
                    ],
                    [
                        'tahun' => $tahun,
                        'tagihan' => $fullAmount,
                        'sisa_tagihan' => $fullAmount - $totalBayarBiaya,
                        'status' => $statusBiaya,
                    ]
                );

                // --- Simpan detail pembayaran dengan kode transaksi ---
                $tagihan->detail()->create([
                    'tgl_bayar' => $tglBayar,
                    'nominal_bayar' => $nominal,
                    'metode_bayar' => $request->metode_bayar,
                    'keterangan' => $keterangan,
                    'kode_transaksi' => $kodeTransaksi, // <-- disini
                ]);

                $totalKas += $nominal;
                $namaBiayaBayar[] = $parBiaya->nama_biaya;
            }

            // --- Update status tagihan siswa ---
            $tagihanSiswa = TagihanLainModel::where('id_pendaftaran', $request->id_pendaftaran)->get();
            foreach ($tagihanSiswa as $t) {
                $t->update([
                    'status' => $t->sisa_tagihan <= 0 ? 'Lunas' : ($t->sisa_tagihan < $t->tagihan ? 'Cicilan' : 'Belum Bayar'),
                ]);
            }

            // --- Simpan ke Kas ---
            $keteranganKas = 'Pembayaran '.implode(', ', $namaBiayaBayar).' atas nama '.$siswa->nama_lengkap.' - '.$keterangan;

            KasModel::create([
                'id_pendaftaran' => $request->id_pendaftaran,
                'tahun' => $tahun,
                'tanggal' => $tglBayar,
                'kd_kas' => 1,
                'keterangan' => $keteranganKas,
                'jumlah' => $totalKas,
            ]);

            DB::commit();

            return response()->json([
                'success' => 'Data Tagihan & Kas berhasil disimpan',
                'kode_transaksi' => $kodeTransaksi, // <-- kembalikan kode transaksi
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
        // Ambil tahun login dari session
        $tahun = session('tahun_login');

        // Ambil semua ParBiaya sesuai tahun login, termasuk yang belum dibayar
        $parBiaya = ParBiayaModel::where('tahun', $tahun)->get();

        $tagihan = TagihanLainModel::with('detail')
            ->where('id_pendaftaran', $id_pendaftaran)
            ->get()
            ->keyBy('id_biaya'); // supaya mudah dicari

        $totalTagihan = $parBiaya->sum(fn ($p) => (float) $p->nominal);
        $totalBayar = 0;

        foreach ($parBiaya as $p) {
            if (isset($tagihan[$p->id_biaya])) {
                $totalBayar += $tagihan[$p->id_biaya]->detail->sum('nominal_bayar');
            }
        }

        $totalSisa = $totalTagihan - $totalBayar;

        // Kirim semua variabel yang dibutuhkan ke view
        return view('private.data.tagihan_lain.show_tagihan_detail', [
            'result' => $tagihan,
            'totalTagihan' => $totalTagihan,
            'totalBayar' => $totalBayar,
            'totalSisa' => $totalSisa,
        ]);
    }

    public function destroy($id)
    {
        if (! request()->ajax()) {
            abort(403, 'Tidak dapat diproses');
        }

        DB::beginTransaction();
        try {
            // Ambil detail + tagihan + pendaftaran
            $detail = TagihanLainDetailModel::with('tagihan.pendaftaran')->find($id);

            if (! $detail) {
                return response()->json([
                    'error' => 'Data pembayaran tidak ditemukan atau sudah dihapus',
                ], 404);
            }

            $tagihan = $detail->tagihan;
            $siswa = $tagihan->pendaftaran;
            $tglBayar = \Carbon\Carbon::parse($detail->tgl_bayar)->toDateString();
            $tahun = \Carbon\Carbon::parse($detail->tgl_bayar)->year;

            // Hapus detail pembayaran
            $detail->delete();

            // Hitung ulang total pembayaran tagihan
            $totalBayar = $tagihan->detail()->sum('nominal_bayar');
            $fullAmount = $tagihan->tagihan;
            $sisa = max(0, $fullAmount - $totalBayar);

            $status = $totalBayar >= $fullAmount ? 'Lunas' :
                      ($totalBayar > 0 ? 'Cicilan' : 'Belum Bayar');

            $tagihan->update([
                'sisa_tagihan' => $sisa,
                'status' => $status,
            ]);

            /**
             * --- 1. Update / hapus kas untuk tanggal pembayaran ini ---
             */
            $totalKasTanggal = TagihanLainDetailModel::whereHas('tagihan', function ($q) use ($tagihan) {
                $q->where('id_pendaftaran', $tagihan->id_pendaftaran);
            })
                ->whereDate('tgl_bayar', $tglBayar)
                ->sum('nominal_bayar');

            $kas = KasModel::where('id_pendaftaran', $tagihan->id_pendaftaran)
                ->whereDate('tanggal', $tglBayar)
                ->where('tahun', $tahun)
                ->where('kd_kas', 1)
                ->first();

            if ($kas) {
                if ($totalKasTanggal > 0) {
                    $kas->update(['jumlah' => $totalKasTanggal]);
                } else {
                    $kas->delete();
                }
            }

            /**
             * --- 2. Kalau semua detail siswa ini sudah kosong → hapus semua kas ---
             */
            $totalKasAll = TagihanLainDetailModel::whereHas('tagihan', function ($q) use ($tagihan) {
                $q->where('id_pendaftaran', $tagihan->id_pendaftaran);
            })
                ->sum('nominal_bayar');

            if ($totalKasAll === 0) {
                KasModel::where('id_pendaftaran', $tagihan->id_pendaftaran)
                    ->where('tahun', $tahun)
                    ->where('kd_kas', 1)
                    ->delete();
            }

            DB::commit();

            return response()->json([
                'success' => 'Pembayaran berhasil dihapus, tagihan & kas diperbarui',
                'myReload' => 'tagihanlaindata',
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus: '.$th->getMessage(),
            ], 500);
        }
    }

    // public function cetak_detail($kode_transaksi)
    // {
    //     // Ambil semua detail pembayaran dalam satu transaksi
    //     $details = TagihanLainDetailModel::with('tagihan.biaya', 'tagihan.pendaftaran')
    //         ->where('kode_transaksi', $kode_transaksi)
    //         ->get();

    //     if ($details->isEmpty()) {
    //         abort(404, 'Transaksi tidak ditemukan.');
    //     }

    //     // Ambil siswa dari detail pertama
    //     $siswa = $details->first()->tagihan->pendaftaran;

    //     $data = [
    //         'siswa' => $siswa,
    //         'details' => $details,
    //     ];

    //     // Generate PDF
    //     $pdf = Pdf::loadView('private.data.tagihan_lain.kwitansi_detail', $data)
    //         ->setPaper('A5', 'landscape');

    //     return $pdf->stream(
    //         'Kwitansi_'.\Illuminate\Support\Str::slug($siswa->nama_lengkap, '_').'_'.$kode_transaksi.'.pdf'
    //     );
    // }

    public function cetak_transaksi($kode_transaksi)
    {
        $details = TagihanLainDetailModel::with('tagihan.biaya', 'tagihan.pendaftaran')
            ->where('kode_transaksi', $kode_transaksi)
            ->get();

        if ($details->isEmpty()) {
            abort(404, 'Transaksi tidak ditemukan.');
        }

        $siswa = $details->first()->tagihan->pendaftaran;

        $data = [
            'siswa' => $siswa,
            'details' => $details,
        ];

        $pdf = Pdf::loadView('private.data.tagihan_lain.kwitansi_transaksi', $data)
            ->setPaper('A5', 'landscape');

        return $pdf->stream('Kwitansi_'.$siswa->nama_lengkap.'_'.$kode_transaksi.'.pdf');
    }

    public function cetak_semua($id_pendaftaran)
    {
        $pendaftaran = PendaftaranModel::with(['tagihanLain.detail', 'siswa'])
            ->findOrFail($id_pendaftaran);

        $data = [
            'siswa' => $pendaftaran,
            'tagihanLain' => $pendaftaran->tagihanLain,
        ];

        $pdf = Pdf::loadView('private.data.tagihan_lain.kwitansi_semua', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Kwitansi_All_'.$pendaftaran->nama_lengkap.'.pdf');
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
