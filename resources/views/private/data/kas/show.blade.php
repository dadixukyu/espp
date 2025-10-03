<!-- Tambahkan animasi hover ringan -->
<style>
    .card:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<!-- Total & Saldo di atas tabel -->
<div class="row mb-4">
    <!-- Total Kas Masuk -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #28a745, #b2d2b7); color: #fff;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Total Kas Masuk</h6>
                    <h5 class="card-text fw-bold">Rp {{ number_format($totalMasuk, 2, ',', '.') }}</h5>
                </div>
                <div>
                    <i class="bx bx-log-in fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Kas Keluar -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #dc3545, #a27c80); color: #fff;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Total Kas Keluar</h6>
                    <h5 class="card-text fw-bold">Rp {{ number_format($totalKeluar, 2, ',', '.') }}</h5>
                </div>
                <div>
                    <i class="bx bx-log-out fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Saldo Saat Ini -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0"
            style="background: linear-gradient(135deg, #0d6efd, #76808f); color: #fff;">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Saldo Saat Ini</h6>
                    <h5 class="card-text fw-bold">Rp {{ number_format($saldo, 2, ',', '.') }}</h5>
                </div>
                <div>
                    <i class="bx bx-wallet fs-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Tabel Data Kas -->
<div class="table-responsive">
    <table id="tabel_kas" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
        style="width:100%">
        <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
            <tr>
                <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                <th class="text-center"><i class="bx bx-cog fs-5"></i></th>
                <th><i class="bx bx-calendar fs-5 me-1"></i> Tanggal</th>
                <th><i class="bx bx-transfer fs-5 me-1"></i> Jenis Kas</th>
                <th><i class="bx bx-detail fs-5 me-1"></i> Keterangan</th>
                <th class="text-end"><i class="bx bx-wallet fs-5 me-1"></i> Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $kas)
                <tr>
                    <td class="text-center align-top">{{ $loop->iteration }}</td>
                    <td class="text-center align-top">
                        <div class="btn-group" role="group">
                            @if (is_null($kas->id_pendaftaran) && is_null($kas->id_siswa))
                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-primary" id="tombol-form-modal"
                                    data-url="{{ route('kasdata.edit', $kas->id_kas) }}" data-bs-toggle="tooltip"
                                    title="Edit Data">
                                    <i class="bx bx-edit-alt fs-5"></i>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form method="POST" action="{{ route('kasdata.destroy', $kas->id_kas) }}"
                                    class="formDelete" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                        title="Hapus Data">
                                        <i class="bx bx-trash-alt fs-5"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                    <td class="text-center align-top">

                        {{ \Carbon\Carbon::parse($kas->tanggal)->locale('id')->translatedFormat('d F Y') }}

                    </td>
                    <td class="text-center align-top">
                        @if ($kas->kd_kas == 1)
                            <span class="badge bg-success"><i class="bx bx-log-in"></i> Kas Masuk</span>
                        @else
                            <span class="badge bg-danger"><i class="bx bx-log-out"></i> Kas Keluar</span>
                        @endif
                    </td>
                    <td class="align-top">{{ $kas->keterangan }}</td>
                    <td class="text-end fw-bold text-primary align-top">
                        Rp {{ number_format($kas->jumlah, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Script DataTables & Tooltip -->
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
                    targets: [0, 1, 3],
                    className: "text-center"
                },
                {
                    targets: 5,
                    className: "text-end"
                }
            ]
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
