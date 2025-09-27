@extends('main')
@section('isi')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-lg rounded-4 border-light">
                <div class="card-body">

                    <!-- Header -->
                    <div
                        class="d-flex align-items-start align-items-md-center gap-3 mb-4 p-4 rounded-3 bg-success-subtle border-start border-4 border-success shadow-sm">
                        <div class="flex-shrink-0">
                            <i class="bx bx-file fs-1 text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">Laporan SPP</h5>
                            <p class="mb-0 text-muted small">
                                <i class="bx bx-info-circle me-1"></i> Proses Laporan SPP Siswa
                            </p>
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <form id="filterForm" class="row g-3 mb-3">
                        <!-- Tahun dari Session -->
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Tahun</label>
                            <input type="text" name="tahun" class="form-control"
                                value="{{ Session::get('tahun_login') }}" readonly>
                        </div>

                        <!-- Tipe Laporan -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tipe Laporan</label>
                            <select name="tipe" id="tipe" class="form-select">
                                <option value="periode">Per Periode (Rekap)</option>
                                <option value="siswa">Per Siswa</option>
                            </select>
                        </div>

                        <!-- Jenis Laporan -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Jenis Laporan</label>
                            <select name="jenis_laporan" id="jenisLaporan" class="form-select">
                                <option value="bulan">Per Bulan</option>
                                <option value="semester">Per Semester</option>
                                <option value="tahun">Per Tahun</option>
                            </select>
                        </div>

                        <!-- Bulan -->
                        <div class="col-md-3" id="bulanWrapper">
                            <label class="form-label fw-bold">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">-- Semua Bulan --</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    @php
                                        \Carbon\Carbon::setLocale('id');
                                    @endphp
                                    <option value="{{ $i }}">
                                        {{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Semester -->
                        <div class="col-md-3 d-none" id="semesterWrapper">
                            <label class="form-label fw-bold">Semester</label>
                            <select name="semester" id="semester" class="form-select">
                                <option value="1">Semester 1 (Jan - Jun)</option>
                                <option value="2">Semester 2 (Jul - Des)</option>
                            </select>
                        </div>

                        <!-- Filter Mode -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Filter</label>
                            <select name="filter_mode" id="filterMode" class="form-select">
                                <option value="all">Semua Siswa</option>
                                <option value="single">Per Siswa</option>
                            </select>
                        </div>

                        <!-- Dropdown Siswa -->
                        <div class="col-md-3 d-none" id="selectSiswaWrapper">
                            <label class="form-label fw-bold">Pilih Siswa</label>
                            <select name="id_siswa" id="selectSiswa" class="form-select">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($daftar_siswa as $siswa)
                                    <option value="{{ $siswa->id_siswa }}">{{ $siswa->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tombol tampilkan -->


                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" onclick="myReloadTable()">
                                <i class="bx bx-search"></i> Tampilkan
                            </button>
                        </div>
                    </form>

                    <!-- Loading Spinner -->
                    <div id="loading-spinner" class="text-center my-3 d-none">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Table View -->
                    <div class="viewTable" style="display:none;"></div>

                </div>
            </div>
        </div>
    </div>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi select2 siswa (searchable)
            $('#selectSiswa').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari atau pilih siswa',
                allowClear: true,
                width: '100%'
            });

            // Saat ganti filter mode
            $('#filterMode').on('change', function() {
                if ($(this).val() === 'single') {
                    $('#selectSiswaWrapper').removeClass('d-none');
                    $('#selectSiswa').select2('open');
                } else {
                    $('#selectSiswaWrapper').addClass('d-none');
                    $('#selectSiswa').val('').trigger('change');
                }
            });

            // Saat ganti jenis laporan
            $('#jenisLaporan').on('change', function() {
                let jenis = $(this).val();
                if (jenis === 'bulan') {
                    $('#bulanWrapper').removeClass('d-none');
                    $('#semesterWrapper').addClass('d-none');
                } else if (jenis === 'semester') {
                    $('#bulanWrapper').addClass('d-none');
                    $('#semesterWrapper').removeClass('d-none');
                } else {
                    $('#bulanWrapper').addClass('d-none');
                    $('#semesterWrapper').addClass('d-none');
                }
            });
        });

        function myReloadTable() {
            let formData = $('#filterForm').serialize();

            $.ajax({
                type: 'GET',
                url: "{{ url('laporansppdata/show') }}",
                data: formData,
                beforeSend: function() {
                    $('#loading-spinner').removeClass('d-none');
                    $('.viewTable').hide();
                },
                success: function(response) {
                    $('.viewTable').html(response).fadeIn();
                },
                complete: function() {
                    $('#loading-spinner').addClass('d-none');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        }
    </script>
@endsection
