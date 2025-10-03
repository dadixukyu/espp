<style>
    .card:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="table-responsive">
    <table id="tabel_tahun_ajaran" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
        style="width:100%">
        <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
            <tr>
                <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                <th><i class="bx bx-calendar-event fs-5 me-1"></i> Tahun Awal</th>
                <th><i class="bx bx-calendar-check fs-5 me-1"></i> Tahun Akhir</th>
                <th><i class="bx bx-book-open fs-5 me-1"></i> Nama Tahun Ajaran</th>
                <th class="text-center"><i class="bx bx-check-shield fs-5 me-1"></i> Status</th>
                <th class="text-center"><i class="bx bx-cog fs-5"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $row)
                <tr>
                    <td class="text-center align-top">{{ $loop->iteration }}</td>
                    <td class="text-center align-top">{{ $row->tahun_awal }}</td>
                    <td class="text-center align-top">{{ $row->tahun_akhir }}</td>
                    <td class="align-top fw-semibold">{{ $row->nama_ta }}</td>
                    <td class="text-center align-top">
                        @if ($row->status === 'aktif')
                            <span class="badge bg-success">
                                <i class="bx bx-check-circle me-1"></i> Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bx bx-x-circle me-1"></i> Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="text-center align-top">
                        <div class="btn-group" role="group">
                            {{-- Tombol Edit --}}
                            <button class="btn btn-sm btn-primary" id="tombol-form-modal"
                                data-url="{{ route('partahundata.edit', $row->id_tahun) }}" data-bs-toggle="tooltip"
                                title="Edit Data">
                                <i class="bx bx-edit-alt fs-5"></i>
                            </button>

                            {{-- Tombol Hapus --}}
                            <form method="POST" action="{{ route('partahundata.destroy', $row->id_tahun) }}"
                                class="formDelete d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                    title="Hapus Data">
                                    <i class="bx bx-trash-alt fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        let table = $('#tabel_tahun_ajaran').DataTable({
            scrollX: true,
            autoWidth: true,
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
            }
        });

        // // Supaya tabel tetap rapi saat zoom/resize
        // $(window).on('resize', function() {
        //     table.columns.adjust();
        // });

        // Aktifkan tooltip Bootstrap
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
