<style>
    /* Animasi hover card */
    .card:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="table-responsive">
    <table id="tabel_biaya" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
        style="width:100%">
        <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
            <tr>
                <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                <th><i class="bx bx-calendar fs-5 me-1"></i> Tahun</th>
                <th><i class="bx bx-label fs-5 me-1"></i> Nama Biaya</th>
                <th><i class="bx bx-money fs-5 me-1"></i> Nominal</th>
                <th><i class="bx bx-info-circle fs-5 me-1"></i> Keterangan</th>
                <th class="text-center"><i class="bx bx-cog fs-5"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $row)
                <tr>
                    <td class="text-center align-top fw-bold">{{ $loop->iteration }}</td>
                    <td class="text-center align-top">
                        {{-- <span class="badge bg-info text-dark px-3 py-2 shadow-sm"> --}}
                        <i class="bx bx-calendar me-1"></i> {{ $row->tahun }}
                        </span>
                    </td>
                    <td class="align-top fw-semibold">
                        <i class="bx bx-label text-primary me-1"></i> {{ $row->nama_biaya }}
                    </td>
                    <td class="align-top text-center">
                        {{-- <span class="badge bg-success px-3 py-2 shadow-sm"> --}}
                        Rp {{ number_format($row->nominal, 2, ',', '.') }}
                        </span>
                    </td>
                    <td class="align-top">
                        <i class="bx bx-info-circle text-secondary me-1"></i> {{ $row->keterangan }}
                    </td>
                    <td class="text-center align-top">
                        <div class="btn-group" role="group">
                            {{-- Tombol Edit --}}
                            <button class="btn btn-sm btn-primary" id="tombol-form-modal"
                                data-url="{{ route('parbiayadata.edit', $row->id_biaya) }}" data-bs-toggle="tooltip"
                                title="Edit Biaya">
                                <i class="bx bx-edit-alt fs-5"></i>
                            </button>

                            {{-- Tombol Delete --}}
                            <form method="POST" action="{{ route('parbiayadata.destroy', $row->id_biaya) }}"
                                class="formDelete d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                    title="Hapus Biaya">
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
        let table = $('#tabel_biaya').DataTable({
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

        // Supaya tabel tetap rapi saat resize/zoom
        $(window).on('resize', function() {
            table.columns.adjust();
        });

        // Aktifkan tooltip Bootstrap
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
