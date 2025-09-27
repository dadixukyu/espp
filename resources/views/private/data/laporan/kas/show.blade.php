@if ($result->count() > 0)

    {{-- Total Kas Card --}}
    <div class="row mb-3 g-2">
        @if ($jenisKas == '1' || $jenisKas == 'all')
            <div class="col-md-4 col-sm-6">
                <div class="card text-white bg-success shadow-sm" style="font-size:0.85rem;">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Total Kas Masuk</span>
                            <span>Rp {{ number_format($totalMasuk, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($jenisKas == '2' || $jenisKas == 'all')
            <div class="col-md-4 col-sm-6">
                <div class="card text-white bg-danger shadow-sm" style="font-size:0.85rem;">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Total Kas Keluar</span>
                            <span>Rp {{ number_format($totalKeluar, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($jenisKas == 'all')
            <div class="col-md-4 col-sm-6">
                <div class="card text-white bg-primary shadow-sm" style="font-size:0.85rem;">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Saldo Saat Ini</span>
                            <span>Rp {{ number_format($saldo, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>



    {{-- Tombol Cetak PDF --}}
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('laporankasdata.cetak_kas_pdf', [
            'tahun' => request('tahun'),
            'bulan' => request('bulan'),
            'jenis_kas' => request('jenis_kas'),
        ]) }}"
            class="btn btn-danger" target="_blank">
            <i class="bx bx-printer"></i> Cetak PDF
        </a>
    </div>

    {{-- Tabel Kas --}}
    <div class="table-responsive">
        <table id="tabel_kas" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
            style="width:100%">
            <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
                <tr>
                    <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                    <th><i class="bx bx-calendar fs-5 me-1"></i> Tanggal</th>
                    <th><i class="bx bx-transfer fs-5 me-1"></i> Jenis Kas</th>
                    <th><i class="bx bx-note fs-5 me-1"></i> Keterangan</th>
                    <th><i class="bx bx-wallet fs-5 me-1"></i> Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($result as $row)
                    <tr>
                        <td class="text-center align-top">{{ $loop->iteration }}</td>
                        <td class="text-center align-top">
                            {{ \Carbon\Carbon::parse($row->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td class="text-center align-top">
                            @if ($row->kd_kas == 1)
                                <span class="badge bg-success"><i class="bx bx-up-arrow-alt"></i> Kas Masuk</span>
                            @else
                                <span class="badge bg-danger"><i class="bx bx-down-arrow-alt"></i> Kas Keluar</span>
                            @endif
                        </td>
                        <td class="align-top">{{ $row->keterangan }}</td>
                        <td class="text-end align-top">Rp {{ number_format($row->jumlah, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-warning text-center shadow-sm rounded">
        <i class="bx bx-info-circle"></i> Tidak ada data kas untuk filter ini.
    </div>
@endif

<script>
    $(document).ready(function() {
        $('#tabel_kas').DataTable({
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
                    targets: [0, 1, 2],
                    className: "text-center"
                },
                {
                    targets: [4],
                    className: "text-end"
                }
            ],
            pageLength: 10,
            responsive: true
        });
    });
</script>
