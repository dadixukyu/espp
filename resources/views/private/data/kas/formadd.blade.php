<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">


            <!-- HEADER MODAL -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bx bx-wallet-alt me-2"></i> Form Transaksi Kas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- FORM START -->
            <form action="{{ route('kasdata.store') }}" class="formData" method="POST">
                @csrf

                <!-- BODY MODAL -->
                <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                    <div class="p-3 border rounded shadow-sm bg-light">

                        <!-- JUDUL FORM -->
                        <h5 class="mb-3 text-primary">
                            <i class="bx bx-edit-alt me-1"></i> Input Kas Masuk / Keluar
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
                                    <i class="bx bx-calendar-event me-1 text-primary"></i> Tanggal
                                </label>
                                <input type="date" name="tanggal" class="form-control">
                            </div>

                            <!-- JENIS KAS -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-transfer me-1 text-primary"></i> Jenis Kas
                                </label>
                                <select name="kd_kas" class="form-control single-select">
                                    <option value="">Pilih...</option>
                                    <option value="1">Kas Masuk</option>
                                    <option value="2">Kas Keluar</option>
                                </select>
                            </div>

                            <!-- KETERANGAN -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-message-detail me-1 text-primary"></i> Keterangan
                                </label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan transaksi..."></textarea>
                            </div>

                            <!-- JUMLAH -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-money me-1 text-success"></i> Jumlah (Rp)
                                </label>
                                <input type="text" id="jumlah" name="jumlah" class="form-control text-end"
                                    placeholder="Rp 0">
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
    // Inisialisasi Select2
    $('.single-select').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Pilih opsi...',
        allowClear: true,
        dropdownParent: $('#modalFormData')
    });

    // Format input rupiah otomatis
    function formatRibuan(angka) {
        if (!angka) return '0';
        angka = angka.replace(/^0+/, '') || '0';
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    $(document).on('input', '#jumlah', function() {
        let raw = this.value.replace(/[^0-9,]/g, '');
        const endsWithComma = raw.endsWith(',');
        const [integerPart, decimalPart = ''] = raw.split(',');
        const formatted = formatRibuan(integerPart);
        this.value = 'Rp ' + formatted + (decimalPart || endsWithComma ? ',' + decimalPart : '');
        this.setSelectionRange(this.value.length, this.value.length);
    });
</script>
