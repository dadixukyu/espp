@extends('main')
@section('isi')
    <!--breadcrumb-->

    <!--end breadcrumb-->
    {{-- <div class="container"> --}}
    {{-- <div class="main-body"> --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"></div>
                    <p class="card-text" align="right">
                        <button data-url="{{ route('userdata.create') }}" type="button" class="btn btn-sm btn-primary"
                            id="tombol-form-modal"><i class="bx bx-user mr-1"></i>&nbsp; Tambah User</button>
                    </p>
                </div>

                <div class="card-body">
                    <div class="border p-4 rounded shadow">
                        <div class="viewTable" style="display:none;"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="mt-3">
                            <h4>USER E-SPP</h4>
                            <p class="text-secondary mb-1">SMK GAJAH MADA PALEMBANG</p>
                        </div>
                        <img src="assets/images/logo_smk.png" alt="Admin" class="rounded-circle p-1 bg-dark"
                            width="110">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
    {{-- </div> --}}
    <div class="viewModal" style="display:none;width:100%"></div>


    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            myReloadTable();
            // Swal.fire(
            //     'Basic alert',
            //     'You clicked the button!')
        });

        function myReloadTable() {
            $.ajax({
                type: 'GET',
                url: "{{ url('userdata/show') }}",
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
