<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanPendaftaranController extends Controller
{
    public function index()
    {
        $tahun = session('tahun_login'); // ambil dari session

        $daftar_siswa = PendaftaranModel::where('tahun', $tahun)
            ->orderBy('nama_lengkap', 'asc')
            ->get(['id_pendaftaran', 'nama_lengkap']);

        return view('private.data.laporan.pendaftaran.view', [
            'title' => 'Laporan Pendaftaran',
            'daftar_siswa' => $daftar_siswa,
        ]);
    }

    public function show(Request $request)
    {
        $tahun = session('tahun_login'); // ambil dari session
        $filter_mode = $request->filter_mode; // all / single
        $id_siswa = $request->id_siswa;

        $query = PendaftaranModel::where('tahun', $tahun);

        if ($filter_mode === 'single' && ! empty($id_siswa)) {
            $query->where('id_pendaftaran', $id_siswa);
        }

        $pendaftaran = $query->orderBy('nama_lengkap')->get();

        return view('private.data.laporan.pendaftaran.show', compact('pendaftaran'));
    }

    public function cetak_pendaftaran_pdf(Request $request)
    {
        $tahun = session('tahun_login'); // ambil dari session
        $filter_mode = $request->filter_mode;
        $id_siswa = $request->id_siswa;

        $query = DB::table('a_pendaftaran')->where('tahun', $tahun);

        if ($filter_mode === 'single' && ! empty($id_siswa)) {
            $query->where('id_pendaftaran', $id_siswa);
        }

        $data = $query->orderBy('nama_lengkap')->get();

        if ($data->isEmpty()) {
            return abort(404, 'Data tidak ditemukan.');
        }

        $jumlah_pendaftar = $data->count();

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'chroot' => public_path(),
        ])->loadView('private.data.laporan.pendaftaran.cetak_pendaftaran_pdf', [
            'data' => $data,
            'tahun' => $tahun,
            'jumlah_pendaftar' => $jumlah_pendaftar,
        ])->setPaper('folio', 'potrait');

        return $pdf->stream("Laporan_Pendaftaran_Siswa_{$tahun}.pdf");
    }
}
