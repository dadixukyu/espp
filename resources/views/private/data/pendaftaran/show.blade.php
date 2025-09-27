<style>
    .card:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="row mb-4">
    <!-- Total Pendaftar -->
    <div class="col-md-2 col-sm-4 mb-2">
        <div class="card shadow-sm border-0"
            style="background: linear-gradient(135deg, #3B82F6, #60A5FA); color: #fff; padding: 0.5rem;">
            <div class="card-body d-flex align-items-center justify-content-between p-2">
                <div>
                    <h6 class="card-title mb-1" style="font-size: 0.8rem;">Total Pendaftar</h6>
                    <h5 class="card-text fw-bold mb-0" style="font-size: 1rem;">
                        <strong>{{ $jumlahPendaftar }}</strong>
                    </h5>
                </div>
                <div>
                    <i class="bx bx-log-in fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendaftar Per Jurusan -->
    @foreach ($jumlahPerJurusan as $item)
        <div class="col-md-2 col-sm-4 mb-2">
            <div class="card shadow-sm border-0"
                style="background: linear-gradient(135deg, #10B981, #34D399); color: #fff; padding: 0.5rem;">
                <div class="card-body d-flex align-items-center justify-content-between p-2">
                    <div>
                        <h6 class="card-title mb-1" style="font-size: 0.8rem;">{{ $item->jurusan }}</h6>
                        <h5 class="card-text fw-bold mb-0" style="font-size: 1rem;">
                            <strong>{{ $item->total }}</strong>
                        </h5>
                    </div>
                    <div>
                        <i class="bx bx-building fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>


<div class="table-responsive">
    <table id="tabel_pendaftaran" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
        style="width:100%">
        <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
            <tr>
                <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                <th class="text-center"><i class="bx bx-cog fs-5"></i></th>
                <th><i class="bx bx-id-card fs-5 me-1"></i> NISN</th>
                <th><i class="bx bx-user fs-5 me-1"></i> Nama Lengkap</th>
                <th><i class="bx bx-male me-1"></i> Gender</th>
                <th><i class="bx bx-map fs-5 me-1"></i> Alamat</th>
                {{-- <th><i class="bx bx-calendar-event fs-5 me-1"></i> Tahun Ajaran</th> --}}
                <th><i class="bx bx-calendar fs-5 me-1"></i> Tahun Masuk</th>
                <th><i class="bx bx-building-house fs-5 me-1"></i> Asal Sekolah</th>
                <th><i class="bx bx-chalkboard fs-5 me-1"></i> Kelas</th>
                <th><i class="bx bx-book-open fs-5 me-1"></i> Jurusan</th>
                <th><i class="bx bx-envelope fs-5 me-1"></i> Email</th>
                <th><i class="bx bx-phone fs-5 me-1"></i> No HP</th>
                <th><i class="bx bx-calendar-check fs-5 me-1"></i> Tgl Daftar</th>
                {{-- <th><i class="bx bx-wallet fs-5 me-1"></i> Biaya Pendaftaran</th> --}}
                <th><i class="bx bx-wallet-alt fs-5 me-1"></i> Kategori Biaya SPP</th>
                <th><i class="bx bx-discount fs-5 me-1"></i> Pengurangan Biaya</th>
                {{-- <th><i class="bx bx-check-shield fs-5 me-1"></i> Status</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $resultAll)
                <tr>
                    <td class="text-center align-top">{{ $loop->iteration }}</td>

                    {{-- Tombol aksi --}}
                    <td class="text-center align-top">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-primary" id="tombol-form-modal"
                                data-url="{{ route('pendaftarandata.edit', $resultAll->id_pendaftaran) }}"
                                data-bs-toggle="tooltip" title="Edit Data">
                                <i class="bx bx-edit-alt fs-5"></i>
                            </button>
                            <form method="POST"
                                action="{{ route('pendaftarandata.destroy', $resultAll->id_pendaftaran) }}"
                                class="formDelete" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip">
                                    <i class="bx bx-trash-alt fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    {{-- Data pendaftaran --}}
                    <td class="align-top">{{ $resultAll->nisn }}</td>
                    <td class="align-top">{{ $resultAll->nama_lengkap }}</td>
                    <td class="align-top">
                        @if ($resultAll->jenis_kelamin == 'L')
                            <span class="badge bg-info text-dark"><i class="bx bx-male fs-6"></i> Laki-laki</span>
                        @else
                            <span class="badge bg-danger"><i class="bx bx-female fs-6"></i> Perempuan</span>
                        @endif
                    </td>
                    <td class="align-top">{{ $resultAll->alamat }}</td>
                    {{-- <td class="text-center align-top">{{ $item->tahun_ajaran }}</td> --}}
                    <td class="text-center align-top">{{ $resultAll->tahun_masuk }}</td>
                    <td class="align-top">{{ $resultAll->asal_sekolah }}</td>
                    <td class="text-center align-top">{{ $resultAll->kelas }}</td>
                    <td class="text-center align-top">{{ $resultAll->jurusan }}</td>
                    <td class="align-top">{{ $resultAll->email ?? '-' }}</td>
                    <td class="align-top">{{ $resultAll->no_hp ?? '-' }}</td>
                    <td class="text-center align-top">
                        {{ \Carbon\Carbon::parse($resultAll->tgl_daftar)->format('d/m/Y') }}
                    </td>
                    {{-- <td class="text-end fw-bold text-primary align-top">
                        Rp {{ number_format($resultAll->biaya_pendaftaran ?? 0, 2, ',', '.') }}
                    </td> --}}
                    <td class="text-end fw-bold text-success align-top">
                        Rp {{ number_format($resultAll->kategori_biaya ?? 0, 2, ',', '.') }}
                    </td>
                    <td class="text-end fw-bold text-danger align-top">
                        Rp {{ number_format($resultAll->pengurangan_biaya ?? 0, 2, ',', '.') }}
                    </td>
                    {{-- <td class="text-center align-top">
                        @if ($resultAll->status_siswa == 'aktif')
                            <span class="badge bg-success"><i class="bx bx-check-circle"></i> Aktif</span>
                        @else
                            <span class="badge bg-danger"><i class="bx bx-x-circle"></i> Tidak Aktif</span>
                        @endif --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

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
            }
        });

        // Tooltip Bootstrap
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
