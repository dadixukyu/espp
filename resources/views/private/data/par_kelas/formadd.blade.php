<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="labelModalSPP" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Header Modal -->
            <div class="modal-header">
                <h5 class="modal-title text-primary">Form Data Tahun Ajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('parkelasdata.store') }}" class="formData" method="POST">
                @csrf

                <!-- Body modal dengan scroll -->
                <div class="modal-body py-2" style="max-height: 65vh; overflow-y: auto;">
                    <div class="border border-2 p-3 rounded">

                        <!-- Judul Form -->
                        <div class="card-title d-flex align-items-center mb-2">
                            <i class="bx bx-money me-2 font-20 text-primary"></i>
                            <h6 class="mb-0 text-primary">
                                {{ $title_form ?? 'FORM INPUT DATA KELAS' }}
                            </h6>
                        </div>
                        <hr class="my-2" />

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" name="kelas" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" name="jurusan" class="form-control">
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Footer Modal -->
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
