<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan SPP {{ $tahun }}</title>
    <style>
        html {
            margin-top: 10px;
            margin-left: 15px;
            margin-right: 15px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 25px;
        }

        .kop {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 30px;
            margin-bottom: 20px;
            position: relative;
        }

        .kop img {
            position: absolute;
            left: 25px;
            top: 5px;
            width: 55px;
            height: auto;
        }

        .kop h2 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
        }

        .kop p {
            margin: 2px 0 0 0;
            font-size: 12px;
        }

        h4 {
            text-align: center;
            margin: 0;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .footer {
            margin-top: 35px;
            text-align: right;
            font-size: 9px;
        }

        /* --- tabel biodata siswa --- */
        .biodata-table,
        .biodata-table td {
            border: none !important;
            padding: 3px 5px;
            font-size: 11px;
        }

        /* --- tabel laporan formal --- */
        .table-formal {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 12px;
        }

        .table-formal th,
        .table-formal td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
            white-space: normal;
            word-break: break-word;
            text-align: left;
            /* default kiri */
        }

        .table-formal th {
            background: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .table-formal th.no,
        .table-formal td.no {
            width: 35px;
            text-align: center;
        }

        .table-formal th.tahun,
        .table-formal td.tahun {
            width: 60px;
            text-align: center;
        }

        .table-formal th.bulan,
        .table-formal td.bulan {
            width: 100px;
        }

        .table-formal th.total,
        .table-formal td.total {
            text-align: right;
            width: 120px;
        }

        .table-formal tfoot th {
            font-weight: bold;
            background: #eaeaea;
        }
    </style>
</head>

<body>
    <div class="kop">
        <img src="{{ public_path('assets/images/logo_smk.png') }}" alt="Logo">
        <h2>PEMERINTAH PROVINSI SUMATERA SELATAN</h2>
        <h2>SMK YP GAJAH MADA PALEMBANG</h2>
        <p>Jl. Banten II, 16 Ulu, Seberang Ulu II, Palembang, Sumatera Selatan</p>
    </div>

    <h4><u>LAPORAN PEMBAYARAN SPP</u></h4>
    <h4>TAHUN {{ $tahun }}</h4>

    {{-- Jika laporan per siswa --}}
    @if ($tipe === 'siswa' && $siswa)
        <table class="biodata-table">
            <tr>
                <td style="width:120px;"><b>Nama</b></td>
                <td style="width:10px;">:</td>
                <td>{{ $siswa->nama_lengkap ?? ($siswa->nama ?? '-') }}</td>
            </tr>
            <tr>
                <td><b>NISN</b></td>
                <td>:</td>
                <td>{{ $siswa->nisn ?? ($siswa->nis ?? '-') }}</td>
            </tr>
            <tr>
                <td><b>Kelas</b></td>
                <td>:</td>
                <td>{{ $siswa->kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Jurusan</b></td>
                <td>:</td>
                <td>{{ $siswa->jurusan ?? '-' }}</td>
            </tr>
        </table>

        <table class="table-formal">
            <thead>
                <tr>
                    <th class="no">No</th>
                    <th class="tahun">Tahun</th>
                    <th class="bulan">Bulan</th>
                    <th class="total">Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $bulanList = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ];
                    $grandTotal = 0;
                @endphp

                @foreach ($bulanList as $num => $namaBulan)
                    @php
                        $row = $result->firstWhere('bulan', $num);
                        $total = $row->total_bayar ?? 0;
                        $grandTotal += $total;
                    @endphp
                    <tr>
                        <td class="no">{{ $loop->iteration }}</td>
                        <td class="tahun">{{ $tahun }}</td>
                        <td class="bulan">{{ $namaBulan }}</td>
                        <td class="total">Rp {{ number_format($total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Keseluruhan</th>
                    <th class="total">Rp {{ number_format($grandTotal, 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    @else
        {{-- Rekap semua siswa --}}
        <table class="table-formal">
            <thead>
                <tr>
                    <th class="no">No</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th class="tahun">Tahun</th>
                    <th class="bulan">Bulan</th>
                    <th class="total">Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($result as $row)
                    @php
                        $total = $row->total_bayar ?? 0;
                        $grandTotal += $total;
                    @endphp
                    <tr>
                        <td class="no">{{ $loop->iteration }}</td>
                        <td>{{ $row->nama_siswa ?? '-' }}</td>
                        <td>{{ $row->nis ?? '-' }}</td>
                        <td>{{ $row->kelas ?? '-' }}</td>
                        <td>{{ $row->jurusan ?? '-' }}</td>
                        <td class="tahun">{{ $tahun }}</td>
                        <td class="bulan">{{ $row->nama_periode ?? '-' }}</td>
                        <td class="total">Rp {{ number_format($total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7">Total Keseluruhan</th>
                    <th class="total">Rp {{ number_format($grandTotal, 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer">
        Palembang, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
        <br><br><br><br><br><br>
        (...................................................)
    </div>
</body>

</html>
