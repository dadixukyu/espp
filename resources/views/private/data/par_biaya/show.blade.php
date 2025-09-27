<style>
    /* Biar tabel tetap rapi saat zoom */
    #tabel_biaya {
        white-space: nowrap;
    }

    /* Border tabel */
    #tabel_biaya th,
    #tabel_biaya td {
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }
</style>

<div class="table-responsive shadow-sm rounded">
    <table id="tabel_biaya" class="table table-hover align-middle" style="width:100%">
        <thead>
            <tr class="bg-primary text-white text-center">
                <th width="1%">#</th>
                <th>Tahun</th>
                <th>Nama Biaya</th>
                <th>Nominal</th>
                <th>Keterangan</th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $resultAll)
                <tr>
                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                    <td>
                        <span class="badge bg-info text-dark px-3 py-2 shadow-sm">
                            <i class="bx bx-calendar me-1"></i> {{ $resultAll->tahun }}
                        </span>
                    </td>
                    <td>
                        <i class="bx bx-label text-primary me-1"></i>
                        <span class="fw-semibold">{{ $resultAll->nama_biaya }}</span>
                    </td>
                    <td>
                        <span class="badge bg-success px-3 py-2 shadow-sm">
                            Rp {{ number_format($resultAll->nominal, 2, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        <i class="bx bx-info-circle text-secondary me-1"></i>
                        {{ $resultAll->keterangan }}
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group" aria-label="aksi">
                            {{-- Tombol Edit --}}
                            <button class="btn btn-sm btn-outline-info d-flex align-items-center me-1"
                                data-url="{{ route('parbiayadata.edit', $resultAll->id_biaya) }}" id="tombol-form-modal"
                                title="Edit Biaya">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>

                            {{-- Tombol Delete --}}
                            <form method="POST" action="{{ route('parbiayadata.destroy', $resultAll->id_biaya) }}"
                                class="formDelete" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center"
                                    title="Hapus Biaya">
                                    <i class="bx bx-trash-alt me-1"></i> Hapus
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
        $('#tabel_biaya').DataTable({
            scrollX: true,
            autoWidth: false,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            }
        });
    });
</script>
