<?php

namespace App\Http\Controllers;

use App\Models\SiswaModel;
use App\Models\TagihanSPPModel;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(Request $request)
    {
        // ===========================
        // Data Siswa Aktif per Kelas
        // ===========================
        $siswaAktif = SiswaModel::select('kelas')
            ->where('status_siswa', 'aktif')
            ->groupBy('kelas')
            ->selectRaw('kelas, COUNT(*) as total')
            ->get();

        $labels = $siswaAktif->pluck('kelas');
        $data = $siswaAktif->pluck('total');

        // Warna batang untuk chart siswa
        $backgroundColor = [];
        $borderColor = [];
        foreach ($labels as $i => $kelas) {
            $r = 50 + ($i * 20);
            $g = 99;
            $b = 255;
            $backgroundColor[] = "rgba($r,$g,$b,0.6)";
            $borderColor[] = "rgba($r,$g,$b,1)";
        }

        $totalSiswa = $data->sum();

        // ===========================
        // Data SPP
        // ===========================

        $tahun = $request->tahun ?? date('Y'); // filter tahun opsional

        $tagihan = TagihanSPPModel::where('tahun', $tahun)->get();

        $totalTagihan = $tagihan->sum('nominal');
        $totalBayar = $tagihan->sum('total_bayar');
        $totalSisa = $tagihan->sum('sisa_tagihan');

        $jumlahLunas = $tagihan->where('status_bayar', 'lunas')->count();
        $jumlahMenunggak = $tagihan->where('status_bayar', 'belum')->count();

        // Data untuk grafik SPP bulanan
        $bulan = range(1, 12);
        $tagihanBulan = [];
        $bayarBulan = [];
        $sisaBulan = [];

        foreach ($bulan as $b) {
            $dataBulan = $tagihan->where('bulan', $b);
            $tagihanBulan[] = $dataBulan->sum('nominal');
            $bayarBulan[] = $dataBulan->sum('total_bayar');
            $sisaBulan[] = $dataBulan->sum('sisa_tagihan');
        }

        return view('private.layout.beranda', compact(
            'labels', 'data', 'backgroundColor', 'borderColor', 'totalSiswa',
            'tahun',
            'totalTagihan', 'totalBayar', 'totalSisa',
            'jumlahLunas', 'jumlahMenunggak',
            'bulan', 'tagihanBulan', 'bayarBulan', 'sisaBulan'
        ));
    }
}
