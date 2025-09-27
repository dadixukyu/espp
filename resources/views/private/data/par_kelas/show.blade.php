<style>
    /* Supaya border tabel tidak pecah saat zoom */
    .dataTables_wrapper .dataTables_scrollBody table {
        border-collapse: collapse !important;
    }
</style>

<div class="table-responsive">
    <table id="tabel_kelas" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded w-100">
        <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #3B82F6);">
            <tr>
                <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                <th><i class="bx bx-chalkboard fs-5 me-1"></i> Kelas</th>
                <th><i class="bx bx-book-open fs-5 me-1"></i> Jurusan</th>
                <th class="text-center"><i class="bx bx-cog fs-5"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $resultAll)
                <tr>
                    <td class="text-center align-top">{{ $loop->iteration }}</td>
                    <td class="fw-semibold align-top">{{ $resultAll->kelas }}</td>
                    <td class="align-top">{{ $resultAll->jurusan }}</td>
                    <td class="text-center align-top">
                        <div class="btn-group" role="group">
                            {{-- Edit --}}
                            <button class="btn btn-sm btn-primary" id="tombol-form-modal"
                                data-url="{{ route('parkelasdata.edit', $resultAll->id_kelas) }}"
                                data-bs-toggle="tooltip" title="Edit Data">
                                <i class="bx bx-edit-alt fs-5"></i>
                            </button>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('parkelasdata.destroy', $resultAll->id_kelas) }}"
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
        let table = $('#tabel_kelas').DataTable({
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


        $(window).on('resize', function() {
            table.columns.adjust();
        });

        // Tooltip Bootstrap
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
