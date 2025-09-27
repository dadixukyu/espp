<style>
    .card:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    /* Scroll container khusus mobile */
    @media (max-width: 768px) {
        .scroll-cards {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 8px;
            scrollbar-width: thin;
            scrollbar-color: #888 transparent;
        }

        .scroll-cards::-webkit-scrollbar {
            height: 6px;
        }

        .scroll-cards::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .scroll-cards::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .scroll-cards .col-card {
            flex: 0 0 auto;
            width: 180px;
        }
    }
</style>

<!-- Container Cards -->
<div class="row row-cols-2 row-cols-md-4 g-2 mb-4 scroll-cards">
    <!-- Total Siswa Aktif -->
    <div class="col col-card">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #4ade80, #22c55e);">
            <div class="card-body d-flex align-items-center justify-content-between p-2">
                <div>
                    <h6 class="card-title mb-1" style="font-size: 0.8rem;">Total Siswa Aktif</h6>
                    <h5 class="card-text fw-bold mb-0" style="font-size: 1rem;">{{ $totalSiswaAktif }}</h5>
                </div>
                <div><i class="bx bx-user fs-3"></i></div>
            </div>
        </div>
    </div>

    <!-- Siswa Kelas X -->
    <div class="col col-card">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #facc15, #eab308);">
            <div class="card-body d-flex align-items-center justify-content-between p-2">
                <div>
                    <h6 class="card-title mb-1" style="font-size: 0.8rem;">Siswa Kelas X</h6>
                    <h5 class="card-text fw-bold mb-0" style="font-size: 1rem;">{{ $jumlahSiswaX }}</h5>
                </div>
                <div><i class="bx bx-book fs-3"></i></div>
            </div>
        </div>
    </div>

    <!-- Siswa Kelas XI -->
    <div class="col col-card">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #10b981, #06b6d4);">
            <div class="card-body d-flex align-items-center justify-content-between p-2">
                <div>
                    <h6 class="card-title mb-1" style="font-size: 0.8rem;">Siswa Kelas XI</h6>
                    <h5 class="card-text fw-bold mb-0" style="font-size: 1rem;">{{ $jumlahSiswaXI }}</h5>
                </div>
                <div><i class="bx bx-book fs-3"></i></div>
            </div>
        </div>
    </div>

    <!-- Siswa Kelas XII -->
    <div class="col col-card">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #f472b6, #ec4899);">
            <div class="card-body d-flex align-items-center justify-content-between p-2">
                <div>
                    <h6 class="card-title mb-1" style="font-size: 0.8rem;">Siswa Kelas XII</h6>
                    <h5 class="card-text fw-bold mb-0" style="font-size: 1rem;">{{ $jumlahSiswaXII }}</h5>
                </div>
                <div><i class="bx bx-book fs-3"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table id="table_tagihan_spp" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
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
                <th><i class="bx bx-time fs-5 me-1"></i> Tahun Masuk</th>
                <th><i class="bx bx-grid fs-5 me-1"></i> Kelas</th>
                <th><i class="bx bx-layer fs-5 me-1"></i> Jurusan</th>

                <th><i class="bx bx-wallet fs-5 me-1"></i> Nominal SPP</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($result as $resultAll)
                <tr>
                    <td class="text-center align-top">{{ $loop->iteration }}</td>


                    <td class="text-center align-top">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-primary" id="tombol-form-modal"
                                data-url="{{ route('tagihan_sppdata.create_tagihan_spp', $resultAll->id_siswa) }}"
                                data-bs-toggle="tooltip" title="input SPP">
                                <i class="bx bx-edit-alt fs-5"></i>
                            </button>
                            {{-- Tombol detail tagihan --}}
                            <button class="btn btn-sm btn-info tombol-detail-tagihan" id="tombol-form-modal"
                                data-url="{{ route('tagihan_sppdata.show_tagihan_spp', $resultAll->id_siswa) }}"
                                data-bs-toggle="tooltip" title="Detail Tagihan SPP">
                                <i class="bx bx-show fs-5"></i>
                            </button>
                        </div>
                    </td>

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

                    <td class="text-center align-top">{{ $resultAll->tahun_masuk }}</td>
                    <td class="text-center align-top">{{ $resultAll->kelas }}</td>
                    <td class="text-center align-top">{{ $resultAll->jurusan }}</td>

                    <td class="text-end text-success fw-bold align-top">
                        Rp {{ number_format($resultAll->nominal_spp, 2, ',', '.') }}
                    </td>

                    {{-- <td class="text-end text-success fw-bold align-top">
                        Rp {{ number_format($resultAll->pengurangan_biaya ?? 0, 2, ',', '.') }}
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#table_tagihan_spp').DataTable({

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


        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
