@extends('main')
@section('isi')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-lg rounded-4 border-light">
                <div class="card-body">

                    <!-- Header -->
                    <div
                        class="d-flex align-items-start align-items-md-center gap-3 mb-4 p-4 rounded-3 bg-primary-subtle border-start border-4 border-primary shadow-sm">
                        <div class="flex-shrink-0">
                            <i class="bx bx-transfer fs-1 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">Pindah Kelas</h5>
                            <p class="mb-0 text-muted small">
                                <i class="bx bx-info-circle me-1"></i> Pilih kelas dan tampilkan siswa untuk pindah kelas
                            </p>
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <form id="filterForm" class="row g-3 mb-3">
                        <!-- Pilih Tahun -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tahun</label>
                            <input type="text" name="tahun" class="form-control"
                                value="{{ Session::get('tahun_login') }}" readonly>
                        </div>

                        <!-- Dropdown Kelas -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pilih Kelas</label>
                            <select name="kelas" id="selectKelas" class="form-select">
                                <option value="">-- Pilih Kelas --</option>
                                <option value="X">Kelas X</option>
                                <option value="XI">Kelas XI</option>
                                <option value="XII">Kelas XII</option>
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
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Table View -->
                    <div class="viewTable" style="display:none;"></div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script>
        function myReloadTable() {
            let kelas = $('#selectKelas').val();

            if (kelas === "") {
                Swal.fire({
                    icon: 'info',
                    title: 'Pilih Kelas',
                    text: 'Silakan pilih kelas terlebih dahulu sebelum melanjutkan.',
                    confirmButtonText: 'Oke, mengerti 👍',
                    confirmButtonColor: '#4e73df',
                    backdrop: true,
                    timer: 2500,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
                return false;
            }


            let formData = $('#filterForm').serialize();

            $.ajax({
                type: 'GET',
                url: "{{ url('pindahkelasdata/show') }}",
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
