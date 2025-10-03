<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kwitansi Pembayaran</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 10mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .kwitansi {
            border: 2px solid #000;
            padding: 10px 15px;
            border-radius: 6px;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .judul {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .subjudul {
            text-align: center;
            font-size: 11px;
            margin-bottom: 8px;
        }

        hr {
            border: 1px solid #000;
            margin: 5px 0 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 3px 2px;
            vertical-align: top;
        }

        .label {
            width: 35%;
            font-weight: bold;
        }

        .value {
            width: 65%;
        }

        .signature {
            margin-top: 20px;
            width: 100%;
        }

        .signature td {
            text-align: center;
            padding-top: 10px;
        }

        .footer {
            margin-top: 5px;
            font-size: 10px;
            text-align: center;
            font-style: italic;
            color: #444;
        }
    </style>
</head>

<body>
    <div class="kwitansi">
        <div class="judul">KWITANSI PEMBAYARAN</div>
        <div class="subjudul">Bukti Pembayaran Resmi Sekolah</div>
        <hr>
        <table>
            <tr>
                <td class="label">Nama Siswa</td>
                <td class="value">: {{ $siswa->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">Kelas</td>
                <td class="value">: {{ $siswa->kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jurusan</td>
                <td class="value">: {{ $siswa->jurusan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Bayar</td>
                <td class="value">: {{ \Carbon\Carbon::parse($detail->tgl_bayar)->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td class="label">Jenis Biaya</td>
                <td class="value">: {{ $biaya->nama_biaya }}</td>
            </tr>
            <tr>
                <td class="label">Nominal Bayar</td>
                <td class="value">: Rp {{ number_format($detail->nominal_bayar, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Metode Pembayaran</td>
                <td class="value">: {{ $detail->metode_bayar }}</td>
            </tr>
            <tr>
                <td class="label">Keterangan</td>
                <td class="value">: {{ $detail->keterangan ?? '-' }}</td>
            </tr>
        </table>

        <table class="signature">
            <tr>
                <td></td>
                <td>Palembang, {{ \Carbon\Carbon::parse($detail->tgl_bayar)->locale('id')->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td><b>Bendahara</b></td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-top: 30px;">( __________________ )</td>
            </tr>
        </table>

        <div class="footer">
            *Simpan kwitansi ini sebagai bukti pembayaran yang sah
        </div>
    </div>
</body>

</html>
