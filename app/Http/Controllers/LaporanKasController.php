<?php

namespace App\Http\Controllers;

use App\Models\KasModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanKasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => '',

        ];

        return view('private.data.laporan.kas.view')->with($data);
    }

    public function show(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan;
        $jenisKas = $request->jenis_kas ?? 'all';

        $query = KasModel::whereYear('tanggal', $tahun);

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        if ($jenisKas == 1 || $jenisKas == 2) {
            $query->where('kd_kas', $jenisKas);
        }

        $result = $query->orderBy('tanggal')->get();

        $totalMasuk = $result->where('kd_kas', 1)->sum('jumlah');
        $totalKeluar = $result->where('kd_kas', 2)->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        return view('private.data.laporan.kas.show', compact(
            'result', 'totalMasuk', 'totalKeluar', 'saldo', 'tahun', 'bulan', 'jenisKas'
        ));
    }

    public function cetak_kas_pdf(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $jenisKas = $request->jenis_kas;

        $query = \DB::table('c_kas');

        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        if ($jenisKas && $jenisKas != 'all') {
            $query->where('kd_kas', $jenisKas);
        }

        $data = $query->orderBy('tanggal')->get();

        $totalMasuk = $data->where('kd_kas', 1)->sum('jumlah');
        $totalKeluar = $data->where('kd_kas', 2)->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        return Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'chroot' => public_path(),
        ])->loadView('private.data.laporan.kas.cetak_kas_pdf', [
            'data' => $data,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'jenisKas' => $jenisKas,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldo' => $saldo,
        ])->setPaper('folio', 'portrait')
            ->stream("Laporan_KAS_{$tahun}".($bulan ? "_Bulan{$bulan}" : '').'.pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
