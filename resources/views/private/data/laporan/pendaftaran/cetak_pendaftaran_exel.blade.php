<table style="width: 100%; border-bottom: 2px solid #000; margin-bottom: 10px; font-family: Arial, sans-serif;">
    <tr>
        <td rowspan="2" style="width: 80px; padding: 5px;">
            <img src="{{ public_path('assets/images/logosumsel-small.png') }}" alt="Logo" width="50">
        </td>
        <td colspan="12" style="text-align: center; font-weight: bold; font-size: 16px;">
            PEMERINTAH {{ strtoupper($data->first()->kd_kab ?? '-') }}
        </td>
    </tr>
    <tr>
        <td colspan="12" style="text-align: center; font-size: 14px;">
            LAPORAN REALISASI KONTRAK PEKERJAAN
        </td>
    </tr>
</table>

<table border="1" cellspacing="0" cellpadding="4"
    style="font-family: Arial, sans-serif; font-size: 10px; border-collapse: collapse; width: 100%;">
    <thead>
        <tr style="background-color: #dbeafe; text-align: center;">
            @php
                $headers = [
                    'No',
                    'No Usulan',
                    'Tanggal',
                    'Provinsi',
                    'Kab/Kota',
                    'No Kontrak',
                    'Nama Rekanan',
                    'Nilai Bayar Tahap 1',
                    'Nilai Bayar Tahap 2',
                    'Nilai Bayar Tahap 3',
                    'Nilai Realisasi Tahap 1',
                    'Nilai Realisasi Tahap 2',
                    'Nilai Realisasi Tahap 3',
                ];
            @endphp
            @foreach ($headers as $head)
                <th style="border: 1px solid #000;">{{ $head }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)
            <tr>
                <td align="center" style="border: 1px solid #000;">{{ $i + 1 }}</td>
                <td style="border: 1px solid #000;">{{ $row->no_usulan }}</td>
                <td style="border: 1px solid #000;">{{ \Carbon\Carbon::parse($row->tgl_usulan)->format('d-m-Y') }}</td>
                <td style="border: 1px solid #000; white-space: normal; word-wrap: break-word">{{ $row->provinsi }}</td>
                <td style="border: 1px solid #000; white-space: normal; word-wrap: break-word">{{ $row->kd_kab }}</td>
                <td style="border: 1px solid #000; white-space: normal; word-wrap: break-word">{{ $row->no_kontrak }}
                </td>
                <td style="border: 1px solid #000; white-space: normal; word-wrap: break-word;">
                    {{ $row->nama_rekanan }}
                </td>
                <td style="border: 1px solid #000;">{{ $row->nilai_tahap1 ?? 0 }}</td>
                <td style="border: 1px solid #000;">{{ $row->nilai_tahap2 ?? 0 }}</td>
                <td style="border: 1px solid #000;">{{ $row->nilai_tahap3 ?? 0 }}</td>
                <td style="border: 1px solid #000;">{{ $row->realisasi_tahap1 ?? 0 }}</td>
                <td style="border: 1px solid #000;">{{ $row->realisasi_tahap2 ?? 0 }}</td>
                <td style="border: 1px solid #000;">{{ $row->realisasi_tahap3 ?? 0 }}</td>




                {{-- @if (!isset($isExcel) || !$isExcel) --}}
            <tr>
                {{-- Kolom A: tetap kosong atau bisa kasih nomor jika mau --}}
                <td style="border: 1px solid #000; background-color: #f9fafb;"></td>

                {{-- Kolom B sampai G digabung, isi Nama Kegiatan --}}
                <td colspan="6"
                    style="border: 1px solid #000; font-style: italic; background-color: #f9fafb; white-space: normal; word-wrap: break-word;">
                    <strong>Nama Kegiatan:</strong> {{ $row->nama_pekerjaan }}
                </td>

                {{-- Kolom H sampai M dikosongkan --}}
                @for ($i = 0; $i < 6; $i++)
                    <td style="border: 1px solid #000; background-color: #f9fafb;"></td>
                @endfor
            </tr>



            {{-- @endif --}}
        @endforeach

        {{-- <tr style="font-weight: bold; background-color: #f0f0f0;">
            <td colspan="7" align="center" style="border: 1px solid #000;">TOTAL</td>
            <td align="right" style="border: 1px solid #000;">
                {{ $total['nilai_tahap1'] }}
            </td>
            <td align="right" style="border: 1px solid #000;">
                {{ $total['nilai_tahap2'] }}
            </td>
            <td align="right" style="border: 1px solid #000;">
                {{ $total['nilai_tahap3'] }}
            </td>
            <td align="right" style="border: 1px solid #000;">
                {{ $total['realisasi_tahap1'] }}
            </td>
            <td align="right" style="border: 1px solid #000;">
                {{ $total['realisasi_tahap2'] }}
            </td>
            <td align="right" style="border: 1px solid #000;">
                {{ $total['realisasi_tahap3'] }}
            </td>
        </tr> --}}

    </tbody>
</table>
