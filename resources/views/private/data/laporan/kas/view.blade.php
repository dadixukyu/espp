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
                            <h5 class="fw-bold mb-1 text-dark">Laporan KAS Masuk dan Kas Keluar</h5>
                            <p class="mb-0 text-muted small">
                                <i class="bx bx-info-circle me-1"></i> Proses Laporan KAS Masuk dan Kas Keluar
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

                        <!-- Dropdown Jenis Kas -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Jenis Kas</label>
                            <select name="jenis_kas" class="form-select">
                                <option value="all">Kas Masuk & Kas Keluar</option>
                                <option value="1">Kas Masuk</option>
                                <option value="2">Kas Keluar</option>
                            </select>
                        </div>

                        <!-- Dropdown Bulan -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">-- Semua Bulan --</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">
                                        {{ \Carbon\Carbon::create(null, $i)->locale('id')->translatedFormat('F') }}
                                    </option>
                                @endfor
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

    <!-- DataTables JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        function myReloadTable() {
            let formData = $('#filterForm').serialize();

            $.ajax({
                type: 'GET',
                url: "{{ url('laporankasdata/show') }}",
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
