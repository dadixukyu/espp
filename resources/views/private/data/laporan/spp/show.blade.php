@if ($data->count() > 0)
    {{-- Tombol Cetak PDF --}}
    <div class="d-flex justify-content-end mb-3">
        @if (request('tipe') === 'periode')
            {{-- Cetak Laporan Periode --}}
            <a class="btn btn-danger btn-sm"
                href="{{ route('laporansppdata.cetak_spp_pdf', [
                    'tahun' => request('tahun'),
                    'filter_mode' => request('filter_mode'),
                    'id_siswa' => request('id_siswa'),
                    'mode_laporan' => request('jenis_laporan'),
                    'bulan' => request('bulan'),
                    'semester' => request('semester'),
                    'tipe' => 'periode',
                ]) }}"
                target="_blank">
                <i class="bx bx-calendar"></i> Cetak Rekap Periode
            </a>
        @elseif (request('tipe') === 'siswa')
            {{-- Cetak Laporan Per Siswa --}}
            <a class="btn btn-danger btn-sm"
                href="{{ route('laporansppdata.cetak_spp_pdf', [
                    'tahun' => request('tahun'),
                    'filter_mode' => request('filter_mode'),
                    'id_siswa' => request('id_siswa'),
                    'mode_laporan' => request('jenis_laporan'),
                    'bulan' => request('bulan'),
                    'semester' => request('semester'),
                    'tipe' => 'siswa',
                ]) }}"
                target="_blank">
                <i class="bx bx-user"></i> Cetak Per Siswa
            </a>
        @endif
    </div>

    {{-- Tabel Data SPP --}}
    <div class="table-responsive">
        <table id="tabel_spp" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
            style="width:100%">
            <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>NISN</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Tanggal Bayar</th>
                    <th>Nominal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $namaBulan = [
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
                @endphp
                @foreach ($data as $row)
                    @php
                        $siswa = $row->tagihan->siswa ?? null;
                        $nama_siswa = $siswa->nama_lengkap ?? ($siswa->nama ?? '-');
                        $nisn = $siswa->nisn ?? ($siswa->nis ?? '-');
                        $kelas = $siswa->kelas ?? '-';
                        $bulan = $namaBulan[$row->bulan] ?? ($row->bulan ?? '-');
                        $tahunBayar = $row->tahun ?? '-';
                        $tglBayar = $row->tgl_bayar ? \Carbon\Carbon::parse($row->tgl_bayar)->format('d/m/Y') : '-';
                        $nominalBayar = $row->nominal_bayar ?? 0;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $nama_siswa }}</td>
                        <td>{{ $nisn }}</td>
                        <td>{{ $kelas }}</td>
                        <td class="text-center">{{ $siswa->jurusan ?? '-' }}</td>
                        <td>{{ $bulan }}</td>
                        <td class="text-center">{{ $tahunBayar }}</td>
                        <td class="text-center">{{ $tglBayar }}</td>
                        <td class="text-end">Rp {{ number_format($nominalBayar, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-warning text-center shadow-sm rounded">
        <i class="bx bx-info-circle"></i> Tidak ada data SPP untuk tahun ini.
    </div>
@endif

{{-- DataTables --}}
<script>
    $(document).ready(function() {
        $('#tabel_spp').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikut",
                    previous: "Sebelumnya"
                },
                zeroRecords: "Data tidak ditemukan"
            },
            columnDefs: [{
                    targets: [0, 4, 5, 7],
                    className: "text-center"
                },
                {
                    targets: [6, 8],
                    className: "text-end"
                }
            ],
            pageLength: 10,
            responsive: true
        });
    });
</script>
