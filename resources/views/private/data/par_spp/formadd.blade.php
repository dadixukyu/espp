<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="labelModalSPP" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header bg-light py-2">
                <h6 class="modal-title text-primary">Form Data SPP</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('parsppdata.store') }}" class="formData" method="POST">
                @csrf

                <div class="modal-body py-2" style="max-height: 65vh; overflow-y: auto;">
                    <div class="border border-2 p-3 rounded">

                        <!-- Judul Form -->
                        <div class="card-title d-flex align-items-center mb-2">
                            <i class="bx bx-money me-2 font-20 text-primary"></i>
                            <h6 class="mb-0 text-primary">
                                {{ $title_form ?? 'FORM INPUT SPP BARU' }}
                            </h6>
                        </div>
                        <hr class="my-2" />


                        {{-- <div class="mb-2">
                            <label class="form-label mb-1">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control form-control-sm" min="2000"
                                max="2099" placeholder="Masukkan tahun SPP">
                        </div> --}}

                        <div class="mb-2">
                            <label class="form-label mb-1">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="text" name="nominal" id="nominal" class="form-control form-control-sm"
                                placeholder="Masukan Nominal SPP (Rp)">
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Keterangan</label>
                            <textarea name="keterangan" class="form-control form-control-sm" id="keterangan"
                                placeholder="Masukan Keterangan SPP jika ada" rows="2"></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer py-2">
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
    // Format input uang otomatis "Rp" dengan titik ribuan
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

        const sisa = angka.length % 3;
        let rupiah = angka.substring(0, sisa);
        const ribuan = angka.substring(sisa).match(/\d{3}/g);

        if (ribuan) {
            const separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }
</script>
