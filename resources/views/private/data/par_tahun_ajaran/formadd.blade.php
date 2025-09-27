<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="labelModalSPP" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Header Modal -->
            <div class="modal-header">
                <h5 class="modal-title text-primary">Form Data Tahun Ajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('partahundata.store') }}" class="formData" method="POST">
                @csrf

                <!-- Body modal dengan scroll -->
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="border border-3 p-3 rounded">

                        <div class="card-title d-flex align-items-center mb-3">
                            <i class="bx bx-money me-2 font-22 text-primary"></i>
                            <h5 class="mb-0 text-primary">
                                {{ $title_form ?? 'FORM INPUT DATA TAHUN AJARAN' }}
                            </h5>
                        </div>
                        <hr />

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tahun_awal" class="form-label">Tahun Awal</label>
                                <input type="number" name="tahun_awal" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="tahun_akhir" class="form-label">Tahun Akhir</label>
                                <input type="number" name="tahun_akhir" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label for="nama_ta" class="form-label">Nama Tahun Ajaran</label>
                                <input type="text" name="nama_ta" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
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

<script>
    // Event input format uang otomatis dengan "Rp" dan titik ribuan
    $(document).on('input', '#nominal', function() {
        let raw = this.value.replace(/[^0-9,]/g, '');
        const endsWithComma = raw.endsWith(',');
        const parts = raw.split(',');
        const integerPart = parts[0];
        const decimalPart = parts[1] ?? '';
        const formattedInteger = formatRibuan(integerPart);
        let result = 'Rp ' + formattedInteger;
        if (decimalPart !== '' || endsWithComma) {
            result += ',' + decimalPart;
        }
        this.value = result;
        this.setSelectionRange(this.value.length, this.value.length);
    });

    function formatRibuan(angka) {
        if (!angka) return '0';
        angka = angka.replace(/^0+/, '') || '0';
        let sisa = angka.length % 3;
        let rupiah = angka.substring(0, sisa);
        let ribuan = angka.substring(sisa).match(/\d{3}/g);
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }
</script>
