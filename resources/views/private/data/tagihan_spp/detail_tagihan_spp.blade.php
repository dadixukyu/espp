<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bx bx-wallet"></i> Input Tagihan SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form id="formTagihanSPP">
                    {{-- Hidden ID Siswa --}}
                    <input type="hidden" name="id_siswa" value="{{ $tmp_id_siswa }}">

                    {{-- Nama Siswa --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Siswa</label>
                        <input type="text" class="form-control" value="{{ $siswa->nama_lengkap ?? '' }}" readonly>
                    </div>

                    {{-- Nominal per bulan --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal SPP per Bulan</label>
                        <input type="text" class="form-control fw-bold text-success"
                            value="Rp {{ number_format(($siswa->kategori_biaya ?? 0) - ($siswa->pengurangan_biaya ?? 0), 2, ',', '.') }}"
                            readonly id="nominal">
                    </div>

                    {{-- Bulan Tagihan --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Bulan Tagihan</label>
                        <div class="row">
                            @php
                                $bulan_all = [
                                    'Januari',
                                    'Februari',
                                    'Maret',
                                    'April',
                                    'Mei',
                                    'Juni',
                                    'Juli',
                                    'Agustus',
                                    'September',
                                    'Oktober',
                                    'November',
                                    'Desember',
                                ];
                                // Pastikan $tagihan_spp tidak null
                                $tagihan_spp = $tagihan_spp ?? collect();
                                $tagihan_sudah = $tagihan_spp->pluck('bulan')->toArray();
                            @endphp
                            @foreach ($bulan_all as $bulan)
                                @php
                                    $lunas =
                                        in_array($bulan, $tagihan_sudah) &&
                                        $tagihan_spp->firstWhere('bulan', $bulan)->status_bayar == 'lunas';
                                @endphp
                                <div class="col-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input bulan-checkbox" type="checkbox" name="bulan[]"
                                            value="{{ $bulan }}" id="bulan_{{ $bulan }}"
                                            {{ $lunas ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="bulan_{{ $bulan }}">
                                            {{ $bulan }} {!! $lunas ? '<span class="text-success">✅</span>' : '' !!}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Total Tagihan --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Tagihan</label>
                        <input type="text" class="form-control fw-bold text-danger" id="total_tagihan" readonly
                            value="Rp 0">
                    </div>

                    {{-- Status Bayar --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Bayar</label>
                        <select name="status_bayar" class="form-select">
                            <option value="belum lunas">Belum Lunas</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button class="btn btn-primary" id="simpanTagihanSPP">Simpan</button>
            </div>

        </div>
    </div>
</div>

<!-- PLUGINS -->
<script src="{{ asset('add-plugins/myscript.js') }}"></script>
<script src="{{ asset('add-plugins/myscriptpost.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Ambil nominal per bulan
        let nominalPerBulan = Number($('#nominal').val().replace(/[Rp\s.]/g, '').replace(',', '.'));

        // Hitung total awal dari 12 bulan
        let totalTagihan = nominalPerBulan * 12;

        // Kurangi bulan yang sudah lunas
        $('.bulan-checkbox:disabled').each(function() {
            totalTagihan -= nominalPerBulan;
        });

        // Tampilkan total awal
        $('#total_tagihan').val(new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(totalTagihan));

        // Update total jika user centang/unclick bulan yang bisa dibayar
        $('.bulan-checkbox:not(:disabled)').on('change', function() {
            let total = totalTagihan; // total awal sudah dikurangi bulan lunas
            $('.bulan-checkbox:not(:disabled):checked').each(function() {
                // Jika dicentang, berarti user memilih membayar, total tetap sama (tidak perlu dikurangi)
                // Jadi logikanya total sudah benar, bisa sesuaikan jika mau menambahkan diskon atau lainnya
            });
            $('#total_tagihan').val(new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(total));
        });
    });
</script>
