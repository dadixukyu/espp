<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
// use PDF;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahun = session('tahun_login');

        $daftar_siswa = PendaftaranModel::where('tahun_masuk', $tahun)
            ->orderBy('nama_lengkap', 'asc')
            ->get(['id_pendaftaran', 'nama_lengkap']);

        return view('private.data.laporan.pendaftaran.view', [
            'title' => 'Laporan Pendaftaran',
            'daftar_siswa' => $daftar_siswa,
        ]);
    }

    public function show(Request $request)
    {
        $tahun = $request->tahun;
        $filter_mode = $request->filter_mode; // all / single
        $id_siswa = $request->id_siswa;

        $query = PendaftaranModel::where('tahun_masuk', $tahun);

        if ($filter_mode === 'single' && ! empty($id_siswa)) {
            $query->where('id_pendaftaran', $id_siswa);
        }

        $pendaftaran = $query->orderBy('nama_lengkap')->get();

        return view('private.data.laporan.pendaftaran.show', compact('pendaftaran'));
    }

    public function cetak_pendaftaran_pdf(Request $request)
    {
        $tahun = $request->tahun;
        $filter_mode = $request->filter_mode;
        $id_siswa = $request->id_siswa;

        // Query data sesuai tahun
        $query = DB::table('a_pendaftaran')->where('tahun_masuk', $tahun);

        // Filter per siswa jika dipilih
        if ($filter_mode === 'single' && ! empty($id_siswa)) {
            $query->where('id_pendaftaran', $id_siswa);
        }

        $data = $query->orderBy('nama_lengkap')->get();

        if ($data->isEmpty()) {
            return abort(404, 'Data tidak ditemukan.');
        }

        $jumlah_pendaftar = $data->count();

        // Generate PDF
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

        // Tampilkan di browser (stream) → bisa diganti download()
        return $pdf->stream("Laporan_Pendaftaran_Siswa_{$tahun}.pdf");
    }

    // public function cetak_realisasi_pdf($id)
    // {
    //     $data = DB::table('aa_a_kontrak as k')
    //         ->join('aa_a_usulan as u', 'u.id_usulan', '=', 'k.id_usulan')
    //         ->join('aa_a_pekerjaan_usulan as p', 'p.id_pekerjaan_u', '=', 'k.id_pekerjaan_u')
    //         ->select(
    //             'u.no_usulan',
    //             'u.tgl_usulan',
    //             DB::raw('(
    //                 SELECT nm_prov FROM aapar_isi_prov
    //                 WHERE kd_prov = u.kd_prov
    //             ) as provinsi'),
    //             DB::raw('(
    //                 SELECT nm_kab FROM aapar_isi_kabkota
    //                 WHERE kd_prov = u.kd_prov AND kd_kab = u.kd_kab
    //             ) as kd_kab'),
    //             'p.nm_keg as nama_pekerjaan',
    //             'k.no_kontrak',
    //             'k.nm_rekanan as nama_rekanan',
    //             'k.nilai_kontrak',
    //             'k.nilai_tahap1',
    //             'k.nilai_tahap2',
    //             'k.nilai_tahap3',
    //             'k.realisasi_tahap1',
    //             'k.realisasi_tahap2',
    //             'k.realisasi_tahap3'
    //         )
    //         ->where('k.id_usulan', $id)
    //         ->get();

    //     if ($data->isEmpty()) {
    //         return abort(404, 'Data tidak ditemukan.');
    //     }

    //     $pdf = PDF::setOptions([
    //         'isHtml5ParserEnabled' => true,
    //         'isRemoteEnabled' => true,
    //         'enable_remote' => true,
    //         'defaultFont' => 'sans-serif',
    //         'chroot' => public_path(),
    //     ])
    //         ->loadView('private.data.laporan.cetak_realisasi.cetak_realisasi_pdf', compact('data'))
    //         ->setPaper('folio', 'landscape');

    //     return $pdf->stream('Laporan_Realisasi_Kontrak.pdf');
    // }

    // public function cetak_realisasi_excel($id)
    // {
    //     $data = DB::table('aa_a_kontrak as k')
    //         ->join('aa_a_usulan as u', 'u.id_usulan', '=', 'k.id_usulan')
    //         ->join('aa_a_pekerjaan_usulan as p', 'p.id_pekerjaan_u', '=', 'k.id_pekerjaan_u')
    //         ->select(
    //             'u.no_usulan',
    //             'u.tgl_usulan',
    //             DB::raw('(SELECT nm_prov FROM aapar_isi_prov WHERE kd_prov = u.kd_prov) as provinsi'),
    //             DB::raw('(SELECT nm_kab FROM aapar_isi_kabkota WHERE kd_prov = u.kd_prov AND kd_kab = u.kd_kab) as kd_kab'),
    //             'p.nm_keg as nama_pekerjaan',
    //             'k.no_kontrak',
    //             'k.nm_rekanan as nama_rekanan',
    //             'k.nilai_tahap1',
    //             'k.nilai_tahap2',
    //             'k.nilai_tahap3',
    //             'k.realisasi_tahap1',
    //             'k.realisasi_tahap2',
    //             'k.realisasi_tahap3'
    //         )
    //         ->where('k.id_usulan', $id)
    //         ->get();

    //     if ($data->isEmpty()) {
    //         abort(404, 'Data tidak ditemukan.');
    //     }

    //     $total = [
    //         'nilai_tahap1' => $data->sum('nilai_tahap1'),
    //         'nilai_tahap2' => $data->sum('nilai_tahap2'),
    //         'nilai_tahap3' => $data->sum('nilai_tahap3'),
    //         'realisasi_tahap1' => $data->sum('realisasi_tahap1'),
    //         'realisasi_tahap2' => $data->sum('realisasi_tahap2'),
    //         'realisasi_tahap3' => $data->sum('realisasi_tahap3'),
    //     ];

    //     return Excel::download(new class($data, $total) implements FromView, WithEvents
    //     {
    //         private $data;

    //         private $total;

    //         public function __construct($data, $total)
    //         {
    //             $this->data = $data;
    //             $this->total = $total;
    //         }

    //         public function view(): \Illuminate\Contracts\View\View
    //         {
    //             return View::make('private.data.laporan.cetak_realisasi.cetak_realisasi_excel', [
    //                 'data' => $this->data,
    //                 'total' => $this->total,
    //                 // 'isExcel' => true,
    //             ]);
    //         }

    //         public function registerEvents(): array
    //         {
    //             return [
    //                 \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
    //                     $sheet = $event->sheet->getDelegate();

    //                     $sheet->getPageSetup()->setOrientation(
    //                         \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
    //                     );

    //                     // Judul
    //                     $sheet->mergeCells('B2:N2');
    //                     $sheet->mergeCells('B3:N3');
    //                     $sheet->getStyle('B2:B3')->applyFromArray([
    //                         'font' => ['bold' => true, 'size' => 14],
    //                         'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    //                     ]);

    //                     $uangKolom = ['H', 'I', 'J', 'K', 'L', 'M'];
    //                     $dataStartRow = 5;
    //                     $lastRow = $sheet->getHighestRow();

    //                     // Deteksi baris yang punya data (kolom G = Nama Rekanan)
    //                     $barisData = [];
    //                     for ($i = $dataStartRow; $i <= $lastRow; $i++) {
    //                         $value = $sheet->getCell("G{$i}")->getValue();
    //                         if (! empty($value)) {
    //                             $barisData[] = $i;
    //                         }
    //                     }

    //                     // Baris total tetap ditampilkan
    //                     $totalRow = ! empty($barisData) ? max($barisData) + 1 : $dataStartRow;

    //                     foreach ($uangKolom as $col) {
    //                         if (! empty($barisData)) {
    //                             $range = implode(',', array_map(fn ($r) => "{$col}{$r}", $barisData));
    //                             $sheet->setCellValue("{$col}{$totalRow}", "=SUM({$range})");
    //                         } else {
    //                             $sheet->setCellValue("{$col}{$totalRow}", 0); // Tampilkan 0 jika tidak ada data
    //                         }

    //                         $sheet->getStyle("{$col}{$totalRow}")->applyFromArray([
    //                             'font' => ['bold' => true],
    //                             'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
    //                         ]);
    //                         $sheet->getStyle("{$col}{$totalRow}")
    //                             ->getNumberFormat()->setFormatCode('#,##0');
    //                     }

    //                     // Label TOTAL
    //                     $sheet->mergeCells("A{$totalRow}:G{$totalRow}");
    //                     $sheet->setCellValue("A{$totalRow}", 'TOTAL');
    //                     $sheet->getStyle("A{$totalRow}")->getAlignment()
    //                         ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //                     $sheet->getStyle("A{$totalRow}:M{$totalRow}")->applyFromArray([
    //                         'font' => ['bold' => true],
    //                         'fill' => [
    //                             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //                             'startColor' => ['rgb' => 'f0f0f0'],
    //                         ],
    //                     ]);

    //                     // Wrap text & ukuran kolom
    //                     foreach (range('D', 'M') as $col) {
    //                         $sheet->getStyle("{$col}1:{$col}{$totalRow}")
    //                             ->getAlignment()->setWrapText(true);
    //                         $sheet->getColumnDimension($col)->setWidth(15);
    //                     }

    //                     foreach (['A', 'B', 'C'] as $col) {
    //                         $sheet->getColumnDimension($col)->setAutoSize(true);
    //                     }

    //                     for ($i = 1; $i <= $totalRow + 5; $i++) {
    //                         $sheet->getRowDimension($i)->setRowHeight(-1);
    //                     }

    //                     $sheet->getStyle("A1:M{$totalRow}")->getAlignment()
    //                         ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    //                 },
    //             ];
    //         }
    //     }, 'Laporan_Realisasi_Kontrak.xlsx');
    // }

    public function create()
    {
        //
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
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
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