<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan SPP Per Siswa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
        }

        h3,
        h4 {
            text-align: center;
            margin: 0;
        }
    </style>
</head>

<body>
    <h3>LAPORAN SPP PER SISWA</h3>
    <h4>Tahun {{ $tahun }}</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS/NISN</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>
                    @if ($mode_laporan === 'bulan')
                        Bulan
                    @elseif($mode_laporan === 'semester')
                        Semester
                    @else
                        Tahun
                    @endif
                </th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->nama_siswa ?? '-' }}</td>
                    <td>{{ $row->nis ?? '-' }}</td>
                    <td>{{ $row->kelas ?? '-' }}</td>
                    <td>{{ $row->jurusan ?? '-' }}</td>
                    <td>{{ $row->nama_periode ?? '-' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($row->total_bayar ?? 0, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="6">Total Keseluruhan</th>
                <th style="text-align: right;">Rp {{ number_format($result->sum('total_bayar'), 2, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <br><br>
    <div style="width:100%; text-align:right;">
        <p>{{ now()->translatedFormat('d F Y') }}</p>
        <p style="margin-top:60px;">(............................)</p>
    </div>
</body>

</html>
