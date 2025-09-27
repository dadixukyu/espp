{{-- Dashboard Cards --}}
<div class="row mb-4">
    {{-- Total Pendaftar --}}
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #28a745, #b2d2b7);">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Total Pendaftar</h6>
                    <h5 class="fw-bold">{{ $jumlahPendaftar }}</h5>
                </div>
                <i class="bx bx-log-in fs-2"></i>
            </div>
        </div>
    </div>

    {{-- Lunas --}}
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #0dcaf0, #87e5ff);">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Lunas</h6>
                    <h5 class="fw-bold">{{ $jumlahLunas }}</h5>
                </div>
                <i class="bx bx-check-circle fs-2"></i>
            </div>
        </div>
    </div>

    {{-- Cicilan --}}
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 text-dark" style="background: linear-gradient(135deg, #ffc107, #ffe58a);">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Cicilan</h6>
                    <h5 class="fw-bold">{{ $jumlahCicil }}</h5>
                </div>
                <i class="bx bx-time fs-2"></i>
            </div>
        </div>
    </div>

    {{-- Belum Bayar --}}
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #dc3545, #f28b9b);">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-2">Belum Bayar</h6>
                    <h5 class="fw-bold">{{ $jumlahBelumBayar }}</h5>
                </div>
                <i class="bx bx-wallet fs-2"></i>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Pendaftaran --}}
<div class="table-responsive">
    <table id="tabel_pendaftaran" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
        style="width:100%">
        <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
            <tr>
                <th class="text-center"><i class="bx bx-hash fs-5"></i></th>
                <th class="text-center"><i class="bx bx-cog fs-5"></i></th>
                <th>Status Tagihan</th>
                <th>NISN</th>
                <th>Nama Lengkap</th>
                <th>Gender</th>
                <th>Alamat</th>
                <th>Tahun Masuk</th>
                <th>Asal Sekolah</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $item)
                <tr>
                    <td class="text-center align-top">{{ $loop->iteration }}</td>
                    <td class="text-center align-top">
                        <div class="btn-group" role="group">
                            {{-- Tombol Input Tagihan --}}
                            <button class="btn btn-sm btn-primary tombol-form-modal" id="tombol-form-modal"
                                data-url="{{ route('tagihanlaindata.create', $item->id_pendaftaran) }}"
                                data-bs-toggle="tooltip" title="Input Tagihan Lain">
                                <i class="bx bx-edit-alt fs-5"></i>
                            </button>

                            <button class="btn btn-sm btn-info tombol-detail-tagihan" id="tombol-form-modal"
                                data-url="{{ route('tagihanlaindata.show_detail', $item->id_pendaftaran) }}"
                                data-bs-toggle="tooltip" title="Detail Tagihan SPP">
                                <i class="bx bx-show fs-5"></i>
                            </button>


                        </div>
                    </td>
                    <td class="align-top">
                        @if ($item->status_siswa == 'Lunas')
                            <span class="badge bg-success"><i class="bx bx-check-circle fs-6"></i> Lunas</span>
                        @elseif($item->status_siswa == 'Cicilan')
                            <span class="badge bg-warning text-dark"><i class="bx bx-time fs-6"></i> Cicilan</span>
                        @else
                            <span class="badge bg-danger"><i class="bx bx-wallet fs-6"></i> Belum Bayar</span>
                        @endif
                    </td>

                    <td class="align-top">{{ $item->nisn }}</td>
                    <td class="align-top">{{ $item->nama_lengkap }}</td>
                    <td class="align-top">
                        {!! $item->jenis_kelamin == 'L'
                            ? '<span class="badge bg-info text-dark"><i class="bx bx-male fs-6"></i> Laki-laki</span>'
                            : '<span class="badge bg-danger"><i class="bx bx-female fs-6"></i> Perempuan</span>' !!}
                    </td>
                    <td class="align-top">{{ $item->alamat }}</td>
                    <td class="text-center align-top">{{ $item->tahun_masuk }}</td>
                    <td class="align-top">{{ $item->asal_sekolah }}</td>
                    <td class="text-center align-top">{{ $item->kelas }}</td>
                    <td class="text-center align-top">{{ $item->jurusan }}</td>
                    <td class="align-top">{{ $item->email ?? '-' }}</td>
                    <td class="align-top">{{ $item->no_hp ?? '-' }}</td>
                    <td class="text-center align-top">
                        {{ \Carbon\Carbon::parse($item->tgl_daftar)->translatedFormat('d F Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Scripts --}}
<script src="{{ asset('add-plugins/myscript.js') }}"></script>
<script src="{{ asset('add-plugins/myscriptpost.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Cek apakah DataTable sudah ada, jika iya destroy dulu
        if ($.fn.DataTable.isDataTable('#tabel_pendaftaran')) {
            $('#tabel_pendaftaran').DataTable().destroy();
        }

        // Inisialisasi DataTable
        $('#tabel_pendaftaran').DataTable({
            responsive: true,
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

        // Tooltip
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Select2 (jika ada)
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    });
</script>
