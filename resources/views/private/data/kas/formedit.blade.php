<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER MODAL -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bx bx-edit-alt me-2"></i> Edit Transaksi Kas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- FORM START -->
            <form action="{{ route('kasdata.update', $kas->id_kas) }}" class="formData" method="POST">
                @csrf
                @method('PUT')

                <!-- BODY MODAL -->
                <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                    <div class="p-3 border rounded shadow-sm bg-light">

                        <!-- JUDUL FORM -->
                        <h5 class="mb-3 text-warning">
                            <i class="bx bx-edit-alt me-1"></i> Perbarui Data Kas
                        </h5>
                        <hr class="mb-4" />

                        <!-- DATA TRANSAKSI -->
                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="bx bx-detail me-1"></i> Data Transaksi
                        </h6>

                        <div class="row g-3">
                            <!-- TANGGAL -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-calendar-event me-1 text-warning"></i> Tanggal
                                </label>
                                <input type="date" name="tanggal" class="form-control"
                                    value="{{ $kas->tanggal->format('Y-m-d') }}" required>
                            </div>

                            <!-- JENIS KAS -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-transfer me-1 text-warning"></i> Jenis Kas
                                </label>
                                <select name="kd_kas" class="form-control single-select" required>
                                    <option value="1" {{ $kas->kd_kas == 1 ? 'selected' : '' }}>Kas Masuk</option>
                                    <option value="2" {{ $kas->kd_kas == 2 ? 'selected' : '' }}>Kas Keluar</option>
                                </select>
                            </div>

                            <!-- KETERANGAN -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-message-detail me-1 text-warning"></i> Keterangan
                                </label>
                                <textarea name="keterangan" class="form-control" rows="3" required>{{ $kas->keterangan }}</textarea>
                            </div>

                            <!-- JUMLAH -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-money me-1 text-success"></i> Jumlah (Rp)
                                </label>
                                <input type="text" id="jumlahEdit" name="jumlah" class="form-control text-end"
                                    value="Rp {{ number_format($kas->jumlah, 0, ',', '.') }}" required>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- FOOTER MODAL -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> TUTUP
                    </button>
                    <button type="reset" class="btn btn-secondary btn-sm">
                        <i class="bx bx-reset me-1"></i> RESET
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnSaveKas">
                        <i class="bx bx-save me-1"></i> SIMPAN
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- PLUGINS -->
<script src="{{ asset('add-plugins/myscript.js') }}"></script>
<script src="{{ asset('add-plugins/myscriptpost.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>


<script>
    // Select2 untuk dropdown edit
    $('.single-select').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Pilih opsi...',
        allowClear: true,
        dropdownParent: $('#modalFormData')
    });

    // Format rupiah untuk jumlah edit
    function formatRibuan(angka) {
        if (!angka) return '0';
        angka = angka.replace(/^0+/, '') || '0';
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    $(document).on('input', '#jumlahEdit', function() {
        let raw = this.value.replace(/[^0-9,]/g, '');
        const endsWithComma = raw.endsWith(',');
        const [integerPart, decimalPart = ''] = raw.split(',');
        const formatted = formatRibuan(integerPart);
        this.value = 'Rp ' + formatted + (decimalPart || endsWithComma ? ',' + decimalPart : '');
        this.setSelectionRange(this.value.length, this.value.length);
    });
</script>
