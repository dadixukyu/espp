@extends('main')
@section('isi')
    <style>
        .hover-shadow:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 123, 255, 0.35) !important;
            transition: 0.3s;
        }
    </style>
    <!-- Header -->
    <div class="d-flex align-items-center mb-4 p-4 rounded bg-primary-subtle border-start border-4 border-primary shadow-sm">
        <i class="bx bx-user fs-1 text-primary me-3"></i>
        <div>
            <h5 class="fw-bold mb-1 text-dark">Pendaftaran Siswa Baru</h5>
            <small class="text-muted"><i class="bx bx-info-circle me-1"></i> Kelola Pendaftaran Siswa Baru</small>
        </div>
    </div>

    <!-- End Breadcrumb -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"></div>
                    <p class="card-text" align="right"> <button data-url="{{ route('pendaftarandata.create') }}"
                            type="button" class="btn btn-sm btn-primary" id="tombol-form-modal"><i
                                class="bx bx-layer-plus mr-1"></i>&nbsp; Tambah Pendaftaan Siswa</button> </p>
                </div>

                <div class="card-body">
                    <div class="border p-3 rounded">
                        <div class="viewTable" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="viewModal" style="display:none;width:100%"></div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            myReloadTable();
        });

        function myReloadTable() {
            $.ajax({
                type: 'GET',
                url: "{{ url('pendaftarandata/show') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#loading-spinner').removeClass('d-none');
                },
                complete: function() {
                    $('#loading-spinner').addClass('d-none');
                },
                success: function(response) {
                    $('.viewTable').html(response).show();
                },
                error: function(xhr, ajaxOptons, throwError) {
                    alert(xhr.status + '\n' + throwError);
                }
            });
        }
    </script>
@endsection
