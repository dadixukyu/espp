<?php

namespace App\Http\Controllers;

use App\Models\SiswaModel;
use App\Models\TagihanSPPDetailModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanSPPController extends Controller
{
    public function index(Request $request)
    {
        $tahun = session('tahun_login') ?? date('Y');
        $idSiswa = $request->id_siswa;

        $daftar_siswa = SiswaModel::getByTahun($tahun, $idSiswa);

        return view('private.data.laporan.spp.view', [
            'title' => 'Laporan SPP',
            'daftar_siswa' => $daftar_siswa,
            'id_siswa' => $idSiswa,
            'filter_mode' => $request->filter_mode ?? 'all',
            'jenis_laporan' => $request->jenis_laporan ?? 'bulan',
        ]);
    }

    public function show(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $jenis = $request->jenis_laporan; // bulan / semester / tahun
        $idSiswa = $request->id_siswa;
        $filterMode = $request->filter_mode;

        $query = TagihanSPPDetailModel::with('tagihan.siswa')
            ->where('tahun', $tahun);

        // filter siswa tunggal
        if ($filterMode === 'single' && $idSiswa) {
            $query->whereHas('tagihan', fn ($q) => $q->where('id_siswa', $idSiswa));
        }

        // filter jenis laporan
        if ($jenis === 'bulan' && $request->bulan) {
            $query->where('bulan', intval($request->bulan));
        } elseif ($jenis === 'semester') {
            $semester = intval($request->semester ?? 1);
            $semester === 1
                ? $query->whereBetween('bulan', [1, 6])
                : $query->whereBetween('bulan', [7, 12]);
        }

        $data = $query->get();

        return view('private.data.laporan.spp.show', compact('data', 'jenis', 'tahun'));
    }

    public function cetak_spp_pdf(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $filter_mode = $request->filter_mode ?? 'all';   // all / single
        $id_siswa = $request->id_siswa;

        $mode_laporan = $request->mode_laporan ?? 'bulan'; // bulan / semester / tahun
        $tipe = $request->tipe ?? 'periode';      // periode / siswa

        // base query (detail)
        $query = TagihanSPPDetailModel::with('tagihan.siswa')
            ->where('tahun', $tahun);

        // filter per-siswa (single)
        if ($filter_mode === 'single' && ! empty($id_siswa)) {
            $query->whereHas('tagihan', function ($q) use ($id_siswa) {
                $q->where('id_siswa', $id_siswa);
            });
        }

        // hanya siswa aktif
        $query->whereHas('tagihan.siswa', function ($q) {
            $q->where('status_siswa', 'aktif');
        });

        // filter bulan / semester sebelum grouping
        if ($mode_laporan === 'bulan' && $request->filled('bulan')) {
            $query->where('bulan', intval($request->bulan));
        } elseif ($mode_laporan === 'semester' && $request->filled('semester')) {
            $semester = intval($request->semester ?? 1);
            $semester === 1
                ? $query->whereBetween('bulan', [1, 6])
                : $query->whereBetween('bulan', [7, 12]);
        }

        // =========================
        // MODE 1: LAPORAN PER PERIODE (rekap)
        // =========================
        if ($tipe === 'periode') {
            if ($mode_laporan === 'bulan') {
                $result = $query->select(
                    'bulan',
                    DB::raw('SUM(COALESCE(nominal_bayar,0)) as total_bayar')
                )
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get()
                    ->map(function ($row) {
                        $bulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        ];
                        $row->nama_periode = $bulan[$row->bulan] ?? $row->bulan;

                        return $row;
                    });
            } elseif ($mode_laporan === 'semester') {
                $result = $query->select(
                    DB::raw('CASE WHEN bulan BETWEEN 1 AND 6 THEN 1 ELSE 2 END as sem_num'),
                    DB::raw("CASE WHEN bulan BETWEEN 1 AND 6 THEN 'Semester 1' ELSE 'Semester 2' END as nama_periode"),
                    DB::raw('SUM(COALESCE(nominal_bayar,0)) as total_bayar')
                )
                    ->groupBy('sem_num', 'nama_periode')
                    ->orderBy('sem_num')
                    ->get();
            } else { // per tahun
                $result = $query->select(
                    DB::raw('tahun as nama_periode'),
                    DB::raw('SUM(COALESCE(nominal_bayar,0)) as total_bayar')
                )
                    ->groupBy('tahun')
                    ->orderBy('tahun')
                    ->get();
            }

            $view = 'private.data.laporan.spp.cetak_spp_periode_pdf';
        }

        // =========================
        // MODE 2: LAPORAN PER SISWA (breakdown)
        // =========================
        else {
            if ($mode_laporan === 'bulan') {
                $result = $query->select(
                    'id_tagihan',
                    'bulan',
                    DB::raw('SUM(COALESCE(nominal_bayar,0)) as total_bayar')
                )
                    ->groupBy('id_tagihan', 'bulan')
                    ->with('tagihan.siswa')
                    ->orderBy('id_tagihan')
                    ->orderBy('bulan')
                    ->get()
                    ->map(function ($row) {
                        $bulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        ];
                        $siswa = $row->tagihan->siswa ?? null;
                        $row->nama_siswa = $siswa->nama_lengkap ?? ($siswa->nama ?? '-');
                        $row->nis = $siswa->nisn ?? ($siswa->nis ?? '-');
                        $row->kelas = $siswa->kelas ?? '-';
                        $row->jurusan = $siswa->jurusan ?? '-';
                        $row->nama_periode = $bulan[$row->bulan] ?? $row->bulan;

                        return $row;
                    });

                // filter bulan sesuai pilihan user
                if ($request->filled('bulan')) {
                    $bulanFilter = intval($request->bulan);
                    $result = $result->filter(fn ($row) => $row->bulan == $bulanFilter);
                }

                if ($filter_mode === 'all') {
                    $result = $result->groupBy('nis')->map(function ($group) {
                        $first = $group->first();

                        return (object) [
                            'nama_siswa' => $first->nama_siswa,
                            'nis' => $first->nis,
                            'kelas' => $first->kelas,
                            'jurusan' => $first->jurusan ?? '-',
                            'total_bayar' => $group->sum('total_bayar'),
                        ];
                    })->values();
                }
            } elseif ($mode_laporan === 'semester') {
                $result = $query->select(
                    'id_tagihan',
                    DB::raw("CASE WHEN bulan BETWEEN 1 AND 6 THEN 'Semester 1' ELSE 'Semester 2' END as nama_periode"),
                    DB::raw('SUM(COALESCE(nominal_bayar,0)) as total_bayar')
                )
                    ->groupBy('id_tagihan', 'nama_periode')
                    ->with('tagihan.siswa')
                    ->orderBy('id_tagihan')
                    ->get();

                if ($request->filled('semester')) {
                    $semFilter = intval($request->semester);
                    $result = $result->filter(fn ($row) => ($semFilter === 1 && strpos($row->nama_periode, '1') !== false) ||
                        ($semFilter === 2 && strpos($row->nama_periode, '2') !== false)
                    );
                }

                $result = $result->map(function ($row) {
                    $siswa = $row->tagihan->siswa ?? null;
                    $row->nama_siswa = $siswa->nama_lengkap ?? ($siswa->nama ?? '-');
                    $row->nis = $siswa->nisn ?? ($siswa->nis ?? '-');
                    $row->kelas = $siswa->kelas ?? '-';

                    return $row;
                });

                if ($filter_mode === 'all') {
                    $result = $result->groupBy('nis')->map(function ($group) {
                        $first = $group->first();

                        return (object) [
                            'nama_siswa' => $first->nama_siswa,
                            'nis' => $first->nis,
                            'kelas' => $first->kelas,
                            'total_bayar' => $group->sum('total_bayar'),
                        ];
                    })->values();
                }
            } else { // per tahun
                $result = $query->select(
                    'id_tagihan',
                    DB::raw('tahun as nama_periode'),
                    DB::raw('SUM(COALESCE(nominal_bayar,0)) as total_bayar')
                )
                    ->groupBy('id_tagihan', 'tahun')
                    ->with('tagihan.siswa')
                    ->orderBy('id_tagihan')
                    ->get()
                    ->map(function ($row) {
                        $siswa = $row->tagihan->siswa ?? null;
                        $row->nama_siswa = $siswa->nama_lengkap ?? ($siswa->nama ?? '-');
                        $row->nis = $siswa->nisn ?? ($siswa->nis ?? '-');
                        $row->kelas = $siswa->kelas ?? '-';

                        return $row;
                    });

                if ($filter_mode === 'all') {
                    $result = $result->groupBy('nis')->map(function ($group) {
                        $first = $group->first();

                        return (object) [
                            'nama_siswa' => $first->nama_siswa,
                            'nis' => $first->nis,
                            'kelas' => $first->kelas,
                            'total_bayar' => $group->sum('total_bayar'),
                        ];
                    })->values();
                }
            }

            $view = 'private.data.laporan.spp.cetak_spp_persiswa_pdf';
        }

        if ($result->isEmpty()) {
            return abort(404, 'Data tidak ditemukan.');
        }

        $siswaObj = null;
        if ($tipe === 'siswa' && $filter_mode === 'single' && ! empty($id_siswa)) {
            $siswaObj = SiswaModel::find($id_siswa);
            if (! $siswaObj) {
                return abort(404, 'Data siswa tidak ditemukan.');
            }
        }

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'chroot' => public_path(),
        ])->loadView($view, [
            'result' => $result,
            'tahun' => $tahun,
            'mode_laporan' => $mode_laporan,
            'tipe' => $tipe,
            'siswa' => $siswaObj,
        ])->setPaper('folio', 'portrait');

        return $pdf->stream("Laporan_SPP_{$tipe}_{$mode_laporan}_{$tahun}.pdf");
    }
}
