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
                            <h5 class="fw-bold mb-1 text-dark">Laporan Pendaftaran</h5>
                            <p class="mb-0 text-muted small">
                                <i class="bx bx-info-circle me-1"></i> Proses Laporan Pendaftaran Siswa Baru
                            </p>
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <form id="filterForm" class="row g-3 mb-3">
                        <!-- Tahun dari Session -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tahun</label>
                            <input type="text" name="tahun" class="form-control"
                                value="{{ Session::get('tahun_login') }}" readonly>
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
                        <div class="col-md-4 d-none" id="selectSiswaWrapper">
                            <label class="form-label fw-bold">Pilih Siswa</label>
                            <select name="id_siswa" id="selectSiswa" class="form-select">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($daftar_siswa as $siswa)
                                    <option value="{{ $siswa->id_pendaftaran }}">{{ $siswa->nama_lengkap }}</option>
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

    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Custom CSS biar select2 konsisten dengan Bootstrap 5 -->
    <style>
        .select2-container--bootstrap-5 .select2-selection {
            min-height: calc(2.25rem + 2px) !important;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--bootstrap-5 .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%);
            right: 0.75rem;
        }

        .select2-container--bootstrap-5 .select2-selection__placeholder {
            color: #6c757d;
        }
    </style>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
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
        });

        function myReloadTable() {
            let formData = $('#filterForm').serialize();

            $.ajax({
                type: 'GET',
                url: "{{ url('laporanpendaftarandata/show') }}",
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
