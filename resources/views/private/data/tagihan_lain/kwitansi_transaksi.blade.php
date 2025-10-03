<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kwitansi Siswa & Arsip</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .kwitansi {
            border: 1.5px solid #000;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .judul {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .subjudul {
            text-align: center;
            font-size: 11px;
            margin-bottom: 10px;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .label {
            width: 35%;
            font-weight: bold;
        }

        .value {
            width: 65%;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .detail-table th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #eaeaea;
        }

        .signature {
            width: 100%;
            margin-top: 20px;
        }

        .signature td {
            text-align: center;
            padding-top: 10px;
        }

        .footer {
            font-size: 10px;
            text-align: center;
            font-style: italic;
            color: #555;
            margin-top: 5px;
        }

        .pemisah {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
            border-top: 2px dashed #000;
            line-height: 0.1em;
        }

        .pemisah span {
            background: #fff;
            padding: 0 10px;
        }
    </style>
</head>

<body>
    {{-- Kwitansi Siswa --}}
    <div class="kwitansi">
        <div class="judul">KWITANSI UNTUK SISWA</div>
        <div class="subjudul">Bukti Pembayaran Resmi Sekolah</div>
        <hr>
        <table>
            <tr>
                <td class="label">Nama Siswa</td>
                <td class="value">: {{ $siswa->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">Jurusan</td>
                <td class="value">: {{ $siswa->jurusan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Bayar</td>
                <td class="value">:
                    {{ \Carbon\Carbon::parse($details->first()->tgl_bayar)->locale('id')->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <table class="detail-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Biaya</th>
                    <th class="text-right">Nominal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $no = 1;
                @endphp
                @foreach ($details as $d)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $d->tagihan->biaya->nama_biaya ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($d->nominal_bayar, 2, ',', '.') }}</td>
                    </tr>
                    @php $total += $d->nominal_bayar; @endphp
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Bayar</td>
                    <td class="text-right">Rp {{ number_format($total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="signature">
            <tr>
                <td></td>
                <td>Palembang,
                    {{ \Carbon\Carbon::parse($details->first()->tgl_bayar)->locale('id')->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td><b>Bendahara</b></td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-top:30px;">( __________________ )</td>
            </tr>
        </table>

        <div class="footer">*Simpan kwitansi ini sebagai bukti pembayaran yang sah</div>
    </div>

    {{-- Garis Pemisah --}}
    <div class="pemisah"><span>-------------------------------------------------------------</span></div>

    {{-- Kwitansi Arsip --}}
    <div class="kwitansi">
        <div class="judul">KWITANSI UNTUK ARSIP SEKOLAH</div>
        <div class="subjudul">Bukti Pembayaran Resmi Sekolah</div>
        <hr>
        <table>
            <tr>
                <td class="label">Nama Siswa</td>
                <td class="value">: {{ $siswa->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">Jurusan</td>
                <td class="value">: {{ $siswa->jurusan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Bayar</td>
                <td class="value">:
                    {{ \Carbon\Carbon::parse($details->first()->tgl_bayar)->locale('id')->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <table class="detail-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Biaya</th>
                    <th class="text-right">Nominal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $no = 1;
                @endphp
                @foreach ($details as $d)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $d->tagihan->biaya->nama_biaya ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($d->nominal_bayar, 2, ',', '.') }}</td>
                    </tr>
                    @php $total += $d->nominal_bayar; @endphp
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Bayar</td>
                    <td class="text-right">Rp {{ number_format($total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="signature">
            <tr>
                <td></td>
                <td>Palembang,
                    {{ \Carbon\Carbon::parse($details->first()->tgl_bayar)->locale('id')->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td><b>Bendahara</b></td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-top:30px;">( __________________ )</td>
            </tr>
        </table>

        <div class="footer">*Simpan kwitansi ini sebagai arsip resmi sekolah</div>
    </div>
</body>

</html>
