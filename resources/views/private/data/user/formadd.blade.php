<div class="modal fade" id="modalFormData" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" style="max-width:850px;">
        <div class="modal-content border-0 shadow-sm rounded-3">

            <form action="{{ route('userdata.store') }}" class="formData" method="POST">
                @csrf
                <input type="hidden" name="kd_opd" id="kd_opd">
                <input type="hidden" name="kd_prov" id="kd_prov">

                <!-- Header -->
                <div class="modal-header bg-primary text-white rounded-top">
                    <h5 class="modal-title"><i class="bx bxs-user me-2"></i> User Registration</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body" style="max-height:85vh; overflow-y:auto;">
                    <div class="row g-3">

                        <!-- Username -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="email" placeholder="Masukkan username"
                                required>
                        </div>

                        <!-- Password -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="Masukkan password"
                                maxlength="25" required>
                        </div>

                        <!-- Level -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                            <select name="level" id="level" class="form-control single-select">
                                <option value="" disabled selected>-- Pilih Level --</option>
                                <option value="1">Admin</option>
                                <option value="2">Kepala Sekolah</option>
                                <option value="3">Operator</option>

                            </select>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="reset" class="btn btn-secondary btn-sm">Reset</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="{{ asset('add-plugins/myscript.js') }}"></script>
<script src="{{ asset('add-plugins/myscriptpost.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
