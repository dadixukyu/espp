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
            padding-bottom: 25px;
            margin-bottom: 15px;
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

        .kop h3 {
            margin: 0;
            font-size: 14px;
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

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 10px;
            line-height: 1.3;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .summary {
            margin-top: 8px;
            font-weight: bold;
            font-size: 11px;
        }

        .footer {
            margin-top: 35px;
            text-align: right;
            font-size: 9px;
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
    <h4><u>LAPORAN PEMBAYARAN SPP</u></h4>
    <h4>TAHUN {{ $tahun }}</h4>

    <p class="summary">Mode Laporan: {{ ucfirst($mode_laporan) }}</p>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Periode</th>
                <th style="width: 120px;">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $key => $row)
                <tr>
                    <td style="text-align:center;">{{ $key + 1 }}</td>
                    <td style="text-align:center;">
                        {{ $row->nama_periode ?? $row->periode }}
                    </td>
                    <td style="text-align:right;">
                        Rp {{ number_format($row->total_bayar, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Palembang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>
</body>

</html>
