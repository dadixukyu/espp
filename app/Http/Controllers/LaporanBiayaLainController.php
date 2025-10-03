<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanBiayaLainController extends Controller
{
    public function index()
    {
        $tahun = session('tahun_login');

        // ambil daftar siswa dari pendaftaran yang punya tagihan biaya lain
        $daftar_siswa = PendaftaranModel::where('tahun', $tahun)
            ->whereHas('tagihanLain') // hanya ambil yg punya data biaya lain
            ->orderBy('nama_lengkap')
            ->get(['id_pendaftaran', 'nama_lengkap']);

        return view('private.data.laporan.biaya_lain.view', [
            'title' => 'Laporan Biaya Lain',
            'daftar_siswa' => $daftar_siswa,
        ]);
    }

    public function show(Request $request)
    {
        $tahun = session('tahun_login');
        $filter_mode = $request->filter_mode;
        $id_pendaftaran = $request->id_pendaftaran;

        $query = PendaftaranModel::with(['tagihanLain.detail'])
            ->where('tahun', $tahun);

        if ($filter_mode === 'single' && ! empty($id_pendaftaran)) {
            $query->where('id_pendaftaran', $id_pendaftaran);
        }

        $pendaftaran = $query->get();

        return view('private.data.laporan.biaya_lain.show', compact('pendaftaran'));
    }

    public function cetak_biaya_pdf(Request $request)
    {
        $tahun = session('tahun_login');
        $filter_mode = $request->filter_mode;
        $id_pendaftaran = $request->id_pendaftaran;

        $query = PendaftaranModel::with(['tagihanLain.detail'])
            ->where('tahun', $tahun);

        if ($filter_mode === 'single' && ! empty($id_pendaftaran)) {
            $query->where('id_pendaftaran', $id_pendaftaran);
        }

        $data = $query->get();

        if ($data->isEmpty()) {
            return abort(404, 'Data tidak ditemukan.');
        }

        // hitung total semua nominal_bayar
        $total_biaya = $data->flatMap(function ($pendaftaran) {
            return $pendaftaran->tagihanLain->flatMap->detail;
        })->sum('nominal_bayar');

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'chroot' => public_path(),
        ])->loadView('private.data.laporan.biaya_lain.cetak_biayalain_pdf', [
            'data' => $data,
            'tahun' => $tahun,
            'total_biaya' => $total_biaya,
        ])->setPaper('folio', 'potrait');

        return $pdf->stream("Laporan_Biaya_Lain_{$tahun}.pdf");
    }
}
