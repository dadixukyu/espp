<style>
    .card:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="table-responsive">
    <table id="tabel_spp" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
        style="width:100%">
        <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
            <tr>
                <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                <th><i class="bx bx-calendar fs-5 me-1"></i> Tahun</th>
                <th><i class="bx bx-money fs-5 me-1"></i> Nominal</th>
                <th><i class="bx bx-note fs-5 me-1"></i> Keterangan</th>
                <th class="text-center"><i class="bx bx-cog fs-5"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $resultAll)
                <tr>
                    <td class="text-center align-top">{{ $loop->iteration }}</td>
                    <td class="fw-semibold align-top">{{ $resultAll->tahun }}</td>
                    <td class="align-top text-center">Rp {{ number_format($resultAll->nominal, 2, ',', '.') }}</td>
                    <td class="align-top">{{ $resultAll->keterangan }}</td>
                    <td class="text-center align-top">
                        <div class="btn-group" role="group">
                            {{-- Edit --}}
                            <button class="btn btn-sm btn-primary" id="tombol-form-modal"
                                data-url="{{ route('parsppdata.edit', $resultAll->id) }}" data-bs-toggle="tooltip"
                                title="Edit SPP">
                                <i class="bx bx-edit-alt fs-5"></i>
                            </button>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('parsppdata.destroy', $resultAll->id) }}"
                                class="formDelete d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                    title="Hapus SPP">
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
        let table = $('#tabel_spp').DataTable({
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
