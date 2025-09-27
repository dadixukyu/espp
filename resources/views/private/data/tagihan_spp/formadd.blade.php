<style>
    /* Scrollbar custom */
    .modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #0b5ed7;
    }
</style>

<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="labelModalSPP" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg rounded-3">

            {{-- Header --}}
            <div class="modal-header bg-primary text-white sticky-top">
                <h5 class="modal-title">
                    <i class="bx bx-wallet me-2"></i> Input Tagihan SPP
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('tagihan_sppdata.store_tagihan_spp') }}" method="POST" class="formData">
                @csrf
                <input type="hidden" name="id_siswa" value="{{ $tmp_id_siswa }}">
                <input type="hidden" id="nominal_spp" name="nominal_spp" value="{{ $nominal_spp }}">

                {{-- Body (scrollable) --}}
                <div class="modal-body" style="max-height:70vh; overflow-y:auto;">

                    {{-- Nama Siswa --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bx bx-user me-1"></i>Nama Siswa</label>
                        <input type="text" class="form-control" value="{{ $siswa->nama_lengkap ?? '' }}" readonly>
                    </div>

                    {{-- Nominal per bulan --}}
                    <div class="mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary rounded-circle d-flex justify-content-center align-items-center"
                                        style="width:40px; height:40px;">
                                        <i class="bx bx-wallet fs-5 text-white"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Nominal SPP Per Bulan</small>
                                        <span class="fw-bold text-primary fs-5">
                                            Rp {{ number_format($nominal_spp, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tanggal Bayar & Metode --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Bayar</label>
                            <input type="date" name="tgl_bayar" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Metode Bayar</label>
                            <select name="metode_bayar" class="form-select" required>
                                <option value="">-- Pilih Metode Bayar --</option>
                                <option value="tunai">💵 Tunai</option>
                            </select>
                        </div>
                    </div>

                    {{-- Bulan Tagihan --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Bulan</label>
                        <div class="row">
                            @foreach ($bulan_status as $no => $b)
                                <div class="col-6 col-md-3 mb-2">
                                    <div
                                        class="form-check p-2 border rounded hover-shadow-sm @if ($b['lunas']) bg-light @endif">
                                        <input type="checkbox" class="form-check-input bulan-checkbox" name="bulan[]"
                                            value="{{ $no }}" id="bulan{{ $no }}"
                                            @if ($b['lunas']) checked disabled title="Bulan ini sudah lunas" @endif>
                                        <label for="bulan{{ $no }}" class="form-check-label ms-1">
                                            {{ $b['nama'] }}
                                            @if ($b['lunas'])
                                                <span class="badge bg-success ms-1">Lunas</span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Total Bayar --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bx bx-calculator me-1"></i>Total Bayar</label>
                        <input type="text" class="form-control fw-bold text-primary" id="totalBayar"
                            name="total_bayar" readonly>
                    </div>

                    {{-- Status Bayar --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Bayar</label>
                        <select name="status_bayar" class="form-select">
                            <option value="belum">⏳ Belum Lunas</option>
                            <option value="lunas">✅ Lunas</option>
                        </select>
                    </div>
                </div>

                {{-- Footer (sticky) --}}
                <div class="modal-footer bg-light sticky-bottom">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> Tutup
                    </button>
                    <button type="reset" class="btn btn-light">
                        <i class="bx bx-reset me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary" id="tombolSave">
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

<script>
    $(document).ready(function() {
        let nominalPerBulan = parseInt($('#nominal_spp').val()) || 0;

        function hitungTotal() {
            let total = 0;
            $('.bulan-checkbox:checked:not(:disabled)').each(function() {
                total += nominalPerBulan;
            });
            $('#totalBayar').val(new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(total));
        }

        $('.bulan-checkbox').on('change', function() {
            hitungTotal();
            $(this).closest('.form-check').toggleClass('border-primary', $(this).is(':checked'));
        });

        $('.formData').on('submit', function() {
            $('#tombolSave').prop('disabled', true).html(
                '<i class="bx bx-loader bx-spin me-1"></i> Menyimpan...');
        });

        hitungTotal();
    });
</script>
