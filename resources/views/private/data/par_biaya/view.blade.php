@extends('main')
@section('isi')
    <!--breadcrumb-->
    <!--end breadcrumb-->
    {{-- <div class="container">
    <div class="main-body"> --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"></div>
                    <p class="card-text" align="right">
                        <button data-url="{{ route('parbiayadata.create') }}" type="button" class="btn btn-sm btn-primary"
                            id="tombol-form-modal"><i class="bx bx-layer-plus mr-1"></i>&nbsp; Tambah Data </button>
                    </p>
                </div>

                <div class="card-body">
                    <div class="border p-4 rounded shadow">
                        <div class="viewTable" style="display:none;"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    {{-- </div>
</div> --}}
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
                url: "{{ url('parbiayadata/show') }}",
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
