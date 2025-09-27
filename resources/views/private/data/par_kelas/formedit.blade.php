<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="labelModalSPP" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">


            <div class="modal-header">
                <h5 class="modal-title text-primary">Form Data SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('parkelasdata.update', $id_kelas) }}" class="formData" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="border border-3 p-3 rounded">

                        <div class="card-title d-flex align-items-center mb-3">
                            <i class="bx bx-money me-2 font-22 text-primary"></i>
                            <h5 class="mb-0 text-primary">
                                {{ $title_form ?? 'FORM INPUT TAHUN AJARAN ' }}
                            </h5>
                        </div>
                        <hr />

                        <div class="row g-3">


                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="kelas" class="form-label">Kelas</label>
                                    <input type="text" name="kelas" class="form-control"
                                        value="{{ $row->kelas }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="jurusan" class="form-label">Jurusan</label>
                                    <input type="text" name="jurusan" class="form-control"
                                        value="{{ $row->jurusan }}">
                                </div>


                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                        TUTUP
                    </button>
                    <button type="reset" class="btn btn-sm btn-secondary">
                        <i class="bx bx-reset me-1"></i> RESET
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary" id="tombolSave">
                        <i class="bx bx-save me-1"></i> SIMPAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="{{ asset('add-plugins/myscript.js') }}"></script>
<script src="{{ asset('add-plugins/myscriptpost.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
