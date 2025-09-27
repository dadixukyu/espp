<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>
        {{ $jenisKas == '1' ? 'Laporan Kas Masuk' : ($jenisKas == '2' ? 'Laporan Kas Keluar' : 'Laporan Kas Masuk & Keluar') }}
        {{ $tahun }} {{ $bulan ? '- ' . \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') : '' }}
    </title>
    <style>
        html {
            margin: 10px 15px;
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
            margin: 0 0 6px 0;
            font-size: 13px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 10px;
            line-height: 1.3;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        td.text-center {
            text-align: center;
        }

        td.text-end {
            text-align: right;
        }

        .footer {
            margin-top: 35px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <!-- KOP SURAT -->
    <div class="kop">
        <img src="{{ public_path('assets/images/logo_smk.png') }}" alt="Logo">
        <h2>PEMERINTAH PROVINSI SUMATERA SELATAN</h2>
        <h2>SMK YP GAJAH MADA PALEMBANG</h2>
        <p>Jl. Banten II, 16 Ulu, Kec. Seberang Ulu II, Kota Palembang, Sumatera Selatan 30116</p>
    </div>

    <!-- JUDUL -->
    <h4><u>
            {{ $jenisKas == '1' ? 'LAPORAN KAS MASUK' : ($jenisKas == '2' ? 'LAPORAN KAS KELUAR' : 'LAPORAN KAS MASUK & KELUAR') }}
        </u></h4>
    <h4>
        TAHUN {{ $tahun }}
        {{ $bulan ? '- ' . \Carbon\Carbon::create(null, $bulan)->locale('id')->translatedFormat('F') : '' }}
    </h4>


    <!-- TABEL KAS -->
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 90px;">Tanggal</th>
                <th style="width: 100px;">Jenis Kas</th>
                <th>Keterangan</th>
                <th style="width: 100px;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $row)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($row->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                    <td class="text-center">{{ $row->kd_kas == 1 ? 'Kas Masuk' : 'Kas Keluar' }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td class="text-end">Rp {{ number_format($row->jumlah, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data kas</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @if ($jenisKas == '1')
                {{-- Kas Masuk --}}
                <tr>
                    <td colspan="4" class="text-center">Total Kas Masuk</td>
                    <td class="text-end">Rp {{ number_format($totalMasuk, 2, ',', '.') }}</td>
                </tr>
            @elseif($jenisKas == '2')
                {{-- Kas Keluar --}}
                <tr>
                    <td colspan="4" class="text-center">Total Kas Keluar</td>
                    <td class="text-end">Rp {{ number_format($totalKeluar, 2, ',', '.') }}</td>
                </tr>
            @else
                {{-- Kas Masuk & Keluar --}}
                <tr>
                    <td colspan="4" class="text-center">Total Kas Masuk</td>
                    <td class="text-end">Rp {{ number_format($totalMasuk, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center">Total Kas Keluar</td>
                    <td class="text-end">Rp {{ number_format($totalKeluar, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center">Saldo Saat Ini</td>
                    <td class="text-end">Rp {{ number_format($saldo, 2, ',', '.') }}</td>
                </tr>
            @endif
        </tfoot>

    </table>

    <!-- FOOTER -->
    <div class="footer">
        Palembang, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }} <br>
    </div>

</body>

</html>
