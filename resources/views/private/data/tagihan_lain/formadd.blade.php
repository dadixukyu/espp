<div class="modal fade" id="modalFormData" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg rounded-3">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bx bx-money-withdraw me-2"></i> Input Tagihan Lain
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Form -->
            <form action="{{ route('tagihanlaindata.store') }}" method="POST" class="formData">
                @csrf
                <input type="hidden" name="id_pendaftaran" value="{{ $pendaftaran->id_pendaftaran }}">

                <!-- Scrollable Body -->
                <div class="modal-body p-3" style="max-height:75vh; overflow-y:auto;">

                    <!-- Info Siswa -->
                    <div class="alert alert-info d-flex justify-content-between align-items-center rounded-3">
                        <div>
                            <strong class="fs-6">{{ $siswa->nama_lengkap }}</strong>
                            <span class="text-muted">(NISN: {{ $siswa->nisn }})</span>
                        </div>
                        <span class="badge bg-dark px-3 py-2">
                            <i class="bx bx-building-house me-1"></i> Kelas: {{ $siswa->kelas ?? '-' }}
                        </span>
                    </div>

                    <!-- Tanggal Bayar -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold">
                            <i class="bx bx-calendar-event me-1 text-primary"></i> Tanggal Bayar
                        </label>
                        <input type="date" name="tgl_bayar" class="form-control shadow-sm" required>
                    </div>

                    <!-- Tabel Biaya Scrollable -->
                    <div class="table-responsive" style="max-height:50vh; overflow-y:auto;">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-primary sticky-top">
                                <tr>
                                    <th>Nama Biaya</th>
                                    <th>Nominal</th>
                                    <th>Sudah Bayar</th>
                                    <th>Sisa</th>
                                    <th width="20%">Bayar Sekarang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parBiaya as $biaya)
                                    <tr>
                                        <td class="fw-semibold">{{ $biaya->nama_biaya }}</td>
                                        <td class="text-end">Rp {{ number_format($biaya->nominal, 0, ',', '.') }}</td>
                                        <td class="text-end text-success">
                                            Rp {{ number_format($biaya->sudah_bayar, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="text-end fw-bold {{ $biaya->sisa == 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format($biaya->sisa, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <input type="text" name="tagihan[{{ $biaya->id_biaya }}]"
                                                class="form-control form-control-sm nominal-input text-end shadow-sm"
                                                placeholder="Masukkan nominal"
                                                @if ($biaya->sisa == 0) disabled @endif>
                                        </td>
                                        <td class="text-center">
                                            @if (strtolower($biaya->status) === 'lunas')
                                                <span class="badge bg-success"><i class="bx bx-check-circle"></i>
                                                    Lunas</span>
                                            @elseif (strtolower($biaya->status) === 'cicil')
                                                <span class="badge bg-warning text-dark"><i class="bx bx-time-five"></i>
                                                    Cicilan</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bx bx-x-circle"></i> Belum
                                                    Bayar</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Pembayaran -->
                    <div class="mt-3 text-end fs-6">
                        <strong>Total Pembayaran Sekarang:</strong>
                        <span id="total_tagihan" class="text-primary fw-bold fs-5">Rp 0</span>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-3 mt-3">
                        <label class="form-label fw-bold">
                            <i class="bx bx-message-detail me-1 text-primary"></i> Keterangan Pembayaran
                        </label>
                        <input type="text" name="keterangan" class="form-control shadow-sm"
                            placeholder="Contoh: Pembayaran SPP bulan September">
                    </div>

                    <!-- Metode Bayar -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bx bx-credit-card-front me-1 text-primary"></i> Metode Pembayaran
                        </label>
                        <select name="metode_bayar" class="form-select form-select-sm single-select shadow-sm" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>

                </div> <!-- End modal-body -->

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> Tutup
                    </button>
                    <button type="reset" class="btn btn-secondary btn-sm">
                        <i class="bx bx-reset me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" id="tombolSave">
                        <i class="bx bx-save me-1"></i> Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<script src="{{ asset('add-plugins/myscript.js') }}"></script>
<script src="{{ asset('add-plugins/myscriptpost.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!-- Script -->
<script>
    function formatRibuan(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    $(document).on('input', '.nominal-input', function() {
        let raw = this.value.replace(/\D/g, '');
        this.value = raw ? formatRibuan(raw) : '';

        let total = 0;
        $('.nominal-input').each(function() {
            let val = this.value.replace(/\D/g, '');
            total += parseInt(val || 0);
        });

        $('#total_tagihan').text('Rp ' + formatRibuan(total.toString()));
    });

    // Inisialisasi select2
    $('.single-select').select2({
        dropdownParent: $('#modalFormData'),
        placeholder: "Pilih Metode Pembayaran",
        width: '100%'
    });
</script>
