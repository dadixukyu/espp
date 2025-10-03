<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Riwayat Pembayaran Siswa</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
            position: relative;
        }

        /* Watermark LUNAS */
        .watermark {
            position: absolute;
            top: 40%;
            left: 0;
            width: 100%;
            font-size: 100px;
            color: rgba(0, 128, 0, 0.08);
            font-weight: bold;
            text-align: center;
            transform: rotate(-30deg);
            z-index: 0;
            pointer-events: none;
        }

        /* Badge LUNAS pojok kanan atas (opsional digital) */
        .badge-lunas {
            position: absolute;
            top: 10mm;
            right: 10mm;
            background-color: #35a54f;
            color: #fff;
            padding: 5px 12px;
            font-weight: bold;
            border-radius: 4px;
            font-size: 12px;
            z-index: 2;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .header img {
            height: 50px;
            position: absolute;
            left: 0;
            top: 0;
        }

        .header .judul-container {
            text-align: center;
        }

        .header .judul-container .judul {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .judul-container .subjudul {
            font-size: 12px;
            color: #333;
            margin-top: 3px;
        }

        hr {
            border: 1px solid #000;
            margin: 5px 0 15px 0;
        }

        /* Identitas siswa */
        .identitas {
            width: 100%;
            margin-bottom: 20px;
            font-size: 12px;
            border-collapse: collapse;
            z-index: 1;
            position: relative;
        }

        .identitas td {
            padding: 4px 2px;
        }

        .identitas .label {
            width: 20%;
            font-weight: bold;
        }

        .identitas .titik {
            width: 2%;
        }

        /* Tabel pembayaran */
        table.pembayaran {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
            z-index: 1;
            position: relative;
        }

        table.pembayaran th,
        table.pembayaran td {
            border: 1px solid #444;
            padding: 6px 8px;
        }

        table.pembayaran th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        table.pembayaran tr:nth-child(even) td {
            background-color: #fafafa;
        }

        table.pembayaran tr.total td {
            background-color: #edf2ef;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            width: 100%;
            font-size: 12px;
            z-index: 1;
            position: relative;
        }

        .footer .bendahara {
            display: inline-block;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- Watermark LUNAS --}}
    @if ($tagihanLain->count() && $tagihanLain->every(fn($t) => strtolower(trim($t->status)) === 'lunas'))
        <div class="watermark">LUNAS</div>
        <div class="badge-lunas">LUNAS</div> {{-- opsional --}}
    @endif

    {{-- Header --}}
    <div class="header">
        <img src="{{ public_path('assets/images/logo_smk.png') }}" alt="Logo">
        <div class="judul-container">
            <div class="judul">Riwayat Pembayaran Siswa</div>
            <div class="subjudul">Bukti Riwayat Pembayaran Resmi Sekolah</div>
        </div>
    </div>
    <hr>

    <!-- Identitas Siswa -->
    <table class="identitas">
        <tr>
            <td class="label">Nama Calon Siswa</td>
            <td class="titik">:</td>
            <td>{{ $siswa->nama_lengkap }}</td>
        </tr>

        <tr>
            <td class="label">Jurusan</td>
            <td class="titik">:</td>
            <td>{{ $siswa->jurusan ?? '-' }}</td>
        </tr>
    </table>

    <!-- Tabel Riwayat Pembayaran -->
    <table class="pembayaran">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Biaya</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $total = 0;
            @endphp
            @foreach ($tagihanLain as $tagihan)
                @foreach ($tagihan->detail as $d)
                    <tr>
                        <td style="text-align:center">{{ $no++ }}</td>
                        <td style="text-align:center">
                            {{ \Carbon\Carbon::parse($d->tgl_bayar)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                        <td>{{ $tagihan->biaya->nama_biaya }}</td>
                        <td class="text-right">Rp {{ number_format($d->nominal_bayar, 2, ',', '.') }}</td>
                        <td>{{ $d->metode_bayar }}</td>
                        <td>{{ $d->keterangan ?? '-' }}</td>
                    </tr>
                    @php $total += $d->nominal_bayar; @endphp
                @endforeach
            @endforeach
            <tr class="total">
                <td colspan="3">TOTAL</td>
                <td colspan="3" class="text-right">Rp {{ number_format($total, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer / Tanda Tangan -->
    <div class="footer">
        <div style="display: flex; justify-content: flex-end;">
            <div class="bendahara">
                Palembang, {{ now()->locale('id')->translatedFormat('d F Y') }}<br>
                Bendahara<br><br><br><br><br><br>
                <div style="border-top: 1px solid #000; width: 180px; margin: 0 auto;">(___________________)</div>
            </div>
        </div>
    </div>

</body>

</html>
