@if ($pendaftaran->count() > 0)
    {{-- Tombol Cetak PDF --}}
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('laporanpendaftarandata.cetak_pendaftaran_pdf', [
            'tahun' => request('tahun'),
            'filter_mode' => request('filter_mode'),
            'id_siswa' => request('id_siswa'),
        ]) }}"
            class="btn btn-danger" target="_blank">
            <i class="bx bx-printer"></i> Cetak PDF
        </a>

    </div>

    <div class="table-responsive">
        <table id="tabel_pendaftaran" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
            style="width:100%">
            <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
                <tr>
                    <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                    <th><i class="bx bx-user fs-5 me-1"></i> Nama Lengkap</th>
                    <th><i class="bx bx-id-card fs-5 me-1"></i> NISN</th>
                    <th><i class="bx bx-male-female fs-5 me-1"></i> Jenis Kelamin</th>
                    <th><i class="bx bx-book fs-5 me-1"></i> Kelas</th>
                    <th><i class="bx bx-git-branch fs-5 me-1"></i> Jurusan</th>
                    <th><i class="bx bx-calendar fs-5 me-1"></i> Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendaftaran as $key => $row)
                    <tr>
                        <td class="text-center align-top">{{ $loop->iteration }}</td>
                        <td class="align-top">{{ $row->nama_lengkap }}</td>
                        <td class="align-top">{{ $row->nisn }}</td>
                        <td class="text-center align-top">
                            @if ($row->jenis_kelamin === 'L')
                                <span class="badge bg-primary"><i class="bx bx-male"></i> Laki-laki</span>
                            @else
                                <span class="badge bg-danger"><i class="bx bx-female"></i> Perempuan</span>
                            @endif
                        </td>
                        <td class="text-center align-top">{{ $row->kelas }}</td>
                        <td class="align-top">{{ $row->jurusan }}</td>
                        <td class="text-center align-top">
                            {{ \Carbon\Carbon::parse($row->tgl_daftar)->format('d/m/Y') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-warning text-center shadow-sm rounded">
        <i class="bx bx-info-circle"></i> Tidak ada data pendaftaran untuk tahun ini.
    </div>
@endif

<script>
    $(document).ready(function() {
        $('#tabel_pendaftaran').DataTable({
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
                targets: [0, 3, 4, 6],
                className: "text-center"
            }],
            pageLength: 10,
            responsive: true
        });

        // Spinner & disable tombol saat cetak
        // $('#btnCetakPDF').on('click', function() {
        //     let $btn = $(this);
        //     $btn.prop('disabled', true);
        //     $btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Menyiapkan PDF…');
        //     setTimeout(function() {
        //         $btn.prop('disabled', false);
        //         $btn.html('<i class="bx bx-printer"></i> Cetak PDF');
        //     }, 5000);
        // });

        // $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
