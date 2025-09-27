<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop='static' data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $title_form }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> --}}
            <form action="{{ route('userdata.updatepassword', $id) }}" class="formData" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-key me-1 font-22 text-info"></i>
                            </div>
                            <h5 class="mb-0 text-info">Reset Password</h5>
                        </div>
                        <hr/>
                        <table class="table-striped">
                            <tr>
                                <td width="30%">Password Default</td>
                                <td width="1%">:</td>
                                <td>123456</td>
                            </tr>
                        </table>
                        <div class="alert alert-danger" role="alert" style="margin-top: 15px;">
                            Apabila di reset maka password akan menjadi default.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button> --}}
                    <button type="button" class="btn grey btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">TUTUP</button>
                        <button type="reset" class="btn btn-sm btn-secondary">
                            <i class='bx bx-reset mr-25'></i>
                            <span class="d-sm-inline">RESET</span>
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary" id="tombolSave">
                            <i class='bx bx-save mr-25'></i> SIMPAN
                        </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="modalFormData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel5" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel5"><b>{{ $title_form }}</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('userdata.updatepassword', $id) }}" class="formData" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="modal-body">
                    <table class="table-striped">
                        <tr>
                            <td width="30%">Password Default</td>
                            <td width="1%">:</td>
                            <td>123456</td>
                        </tr>
                    </table>
                    <div class="alert alert-success" role="alert" style="margin-top: 15px;">
                        Apabila di reset maka password akan menjadi default.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">TUTUP</button>
                    <button type="submit" class="btn-send btn btn-primary btn-glow" id="tombolSave">
                        <i class='feather icon-play mr-25'></i> <span class="d-sm-inline">RESET</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
<script src="{{ asset('add-plugins/myscriptpost.js') }}"></script>
