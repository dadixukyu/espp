<!-- Rekap Status Siswa -->
<div class="alert alert-info mb-3">
    <div class="d-flex justify-content-start flex-wrap gap-2">
        🏷️ Rekap status siswa:
        @foreach (['aktif' => 'success', 'lulus' => 'primary', 'pindah' => 'warning', 'keluar' => 'danger'] as $statusKey => $color)
            <span class="badge bg-{{ $color }} mx-1">
                {{ ucfirst($statusKey) }}: <strong>{{ $rekapPerStatus[$statusKey] ?? 0 }}</strong>
            </span>
        @endforeach
    </div>
</div>

<!-- Tabel Siswa Sesuai Status -->
<div class="table-responsive mt-3">
    <table id="tblSiswaStatus" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
        style="width:100%">
        <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
            <tr>
                <th>No.</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->nisn }}</td>
                    <td>{{ $item->nama_lengkap }}</td>
                    <td class="text-center">{{ $item->kelas }}</td>
                    <td class="text-center">{{ $item->jurusan }}</td>
                    <td class="text-center">
                        @php
                            $statusColor = [
                                'aktif' => 'success',
                                'lulus' => 'primary',
                                'pindah' => 'warning',
                                'keluar' => 'danger',
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColor[$item->status_siswa] ?? 'secondary' }}">
                            @if ($item->status_siswa == 'aktif')
                                <i class="bx bx-check-circle"></i> Aktif
                            @elseif($item->status_siswa == 'lulus')
                                <i class="bx bx-award"></i> Lulus
                            @elseif($item->status_siswa == 'pindah')
                                <i class="bx bx-transfer"></i> Pindah
                            @elseif($item->status_siswa == 'keluar')
                                <i class="bx bx-x-circle"></i> Keluar
                            @else
                                -
                            @endif
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- DataTables Init -->
<script>
    $(document).ready(function() {
        $('#tblSiswaStatus').DataTable({
            destroy: true,
            responsive: true,
            autoWidth: false,
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
    });
</script>
