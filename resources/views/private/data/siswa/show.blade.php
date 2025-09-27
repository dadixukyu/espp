<div class="row mb-4">
    <!-- Total Siswa Aktif -->
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #3B82F6, #60A5FA);">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Total Siswa Aktif</h6>
                    <h5 class="fw-bold">{{ $totalSiswaAktif }}</h5>
                </div>
                <i class="bx bx-user fs-2"></i>
            </div>
        </div>
    </div>

    <!-- Total Siswa Kelas X -->
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #10B981, #34D399);">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Total Siswa Kelas X</h6>
                    <h5 class="fw-bold">{{ $jumlahSiswaX }}</h5>
                </div>
                <i class="bx bx-book-reader fs-2"></i>
            </div>
        </div>
    </div>

    <!-- Total Siswa Kelas XI -->
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #FBBF24, #FCD34D);">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Total Siswa Kelas XI</h6>
                    <h5 class="fw-bold">{{ $jumlahSiswaXI }}</h5>
                </div>
                <i class="bx bx-book fs-2"></i>
            </div>
        </div>
    </div>

    <!-- Total Siswa Kelas XII -->
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #F87171, #FCA5A5);">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Total Siswa Kelas XII</h6>
                    <h5 class="fw-bold">{{ $jumlahSiswaXII }}</h5>
                </div>
                <i class="bx bx-book-open fs-2"></i>
            </div>
        </div>
    </div>
</div>




<div class="table-responsive">
    <table id="tabel_siswa" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
        style="width:100%">
        <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
            <tr>
                <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                <th class="text-center"><i class="bx bx-cog fs-5"></i></th>
                <th class="text-center"><i class="bx bx-check-shield fs-5 me-1"></i> Status Siswa</th>
                <th><i class="bx bx-id-card fs-5 me-1"></i> NISN</th>
                <th><i class="bx bx-user fs-5 me-1"></i> Nama Siswa</th>
                <th><i class="bx bx-male me-1"></i> Gender</th>
                <th><i class="bx bx-map fs-5 me-1"></i> Tempat Lahir</th>
                <th><i class="bx bx-calendar fs-5 me-1"></i> Tgl Lahir</th>
                <th><i class="bx bx-book fs-5 me-1"></i> Agama</th>
                <th><i class="bx bx-home fs-5 me-1"></i> Alamat Siswa</th>
                <th><i class="bx bx-envelope fs-5 me-1"></i> Email</th>
                <th><i class="bx bx-phone fs-5 me-1"></i> No HP</th>
                <th><i class="bx bx-time fs-5 me-1"></i> Tahun Masuk</th>
                <th><i class="bx bx-grid fs-5 me-1"></i> Kelas</th>
                <th><i class="bx bx-layer fs-5 me-1"></i> Jurusan</th>

                <th><i class="bx bx-male fs-5 me-1"></i> Nama Ayah</th>
                <th><i class="bx bx-briefcase fs-5 me-1"></i> Pekerjaan Ayah</th>
                <th><i class="bx bx-female fs-5 me-1"></i> Nama Ibu</th>
                <th><i class="bx bx-briefcase fs-5 me-1"></i> Pekerjaan Ibu</th>
                <th><i class="bx bx-user-pin fs-5 me-1"></i> Nama Wali</th>
                <th><i class="bx bx-phone-call fs-5 me-1"></i> No HP Wali</th>
                <th><i class="bx bx-map-pin fs-5 me-1"></i> Alamat Wali</th>

                <th><i class="bx bx-wallet fs-5 me-1"></i> Kategori Biaya SPP</th>
                <th><i class="bx bx-discount fs-5 me-1"></i> Pengurangan Biaya SPP</th>
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
                                data-url="{{ route('siswadata.edit', $resultAll->id_siswa) }}" data-bs-toggle="tooltip"
                                title="Lengkapi Data Siswa">
                                <i class="bx bx-edit-alt fs-5"></i>
                            </button>
                            <form method="POST" action="{{ route('siswadata.destroy', $resultAll->id_siswa) }}"
                                class="formDelete" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip">
                                    <i class="bx bx-trash-alt fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    {{-- Status siswa --}}
                    <td class="text-center align-top">
                        @switch($resultAll->status_siswa)
                            @case('aktif')
                                <span class="badge bg-success"><i class="bx bx-check-circle"></i> Aktif</span>
                            @break

                            @case('lulus')
                                <span class="badge bg-primary"><i class="bx bx-award"></i> Lulus</span>
                            @break

                            @case('pindah')
                                <span class="badge bg-warning text-dark"><i class="bx bx-transfer"></i> Pindah</span>
                            @break

                            @case('keluar')
                                <span class="badge bg-danger"><i class="bx bx-x-circle"></i> Keluar</span>
                            @break
                        @endswitch
                    </td>

                    {{-- Data siswa utama --}}
                    <td class="align-top">{{ $resultAll->nisn }}</td>
                    <td class="align-top">{{ $resultAll->nama_lengkap }}</td>
                    <td class="align-top">
                        @if ($resultAll->jenis_kelamin == 'L')
                            <span class="badge bg-info text-dark"><i class="bx bx-male fs-6"></i> Laki-laki</span>
                        @else
                            <span class="badge bg-danger"><i class="bx bx-female fs-6"></i> Perempuan</span>
                        @endif
                    </td>
                    <td class="align-top">{{ $resultAll->tempat_lahir }}</td>
                    <td class="align-top">
                        {{ \Carbon\Carbon::parse($resultAll->tanggal_lahir)->locale('id')->isoFormat('D MMMM YYYY') }}
                    </td>
                    <td class="text-center align-top">{{ $resultAll->agama }}</td>
                    <td class="align-top kol-uraian" style="white-space: normal !important; word-wrap: break-word;">
                        {{ $resultAll->alamat }}</td>
                    <td class="align-top">{{ $resultAll->email }}</td>
                    <td class="align-top">{{ $resultAll->no_hp }}</td>

                    <td class="text-center align-top">{{ $resultAll->tahun_masuk }}</td>
                    <td class="text-center align-top">{{ $resultAll->kelas }}</td>
                    <td class="text-center align-top">{{ $resultAll->jurusan }}</td>



                    {{-- Orang tua / wali --}}
                    <td class="text-center align-top">{{ $resultAll->nama_ayah }}</td>
                    <td class="text-center align-top">{{ $resultAll->pekerjaan_ayah }}</td>
                    <td class="text-center align-top">{{ $resultAll->nama_ibu }}</td>
                    <td class="text-center align-top">{{ $resultAll->pekerjaan_ibu }}</td>
                    <td class="text-center align-top">{{ $resultAll->nama_wali }}</td>
                    <td class="text-center align-top">{{ $resultAll->no_hp_wali }}</td>
                    <td class="align-top kol-uraian" style="white-space: normal !important; word-wrap: break-word;">
                        {{ $resultAll->alamat_wali }}</>

                        {{-- SPP & Pengurangan --}}
                    <td class="text-end text-success fw-bold align-top">
                        Rp {{ number_format($resultAll->kategori_biaya ?? 0, 2, ',', '.') }}
                    </td>

                    <td class="text-end text-danger fw-bold align-top">
                        Rp {{ number_format($resultAll->pengurangan_biaya ?? 0, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#tabel_siswa').DataTable({
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
