<div class="modal fade" id="modalFormData" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg rounded-3 border-0">

            {{-- Modal Header --}}
            <div class="modal-header bg-gradient bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="bx bx-receipt fs-4 me-2"></i> Detail Tagihan SPP
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body">
                @if ($result->isEmpty())
                    <div class="alert alert-info text-center mb-0">
                        <i class="bx bx-info-circle"></i> Belum ada data tagihan SPP untuk siswa ini.
                    </div>
                @else
                    @foreach ($result as $item)
                        <div class="card mb-3 shadow-sm border-0">

                            {{-- Header per bulan --}}
                            <div
                                class="card-header bg-light d-flex justify-content-between align-items-center rounded-top">
                                <div>
                                    <strong class="text-dark">
                                        <i class="bx bx-calendar me-1"></i>
                                        {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}
                                        {{ $item->tahun }}
                                    </strong>
                                    <div class="text-muted small">
                                        <i class="bx bx-money me-1"></i> Tagihan:
                                        <span class="fw-semibold text-dark">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span
                                        class="badge px-3 py-2 rounded-pill 
                                            @if ($item->status_bayar == 'lunas') bg-success
                                            @elseif($item->detail->count() > 0) bg-warning text-dark
                                            @else bg-danger @endif">
                                        {{ ucfirst($item->status_bayar) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Riwayat pembayaran --}}
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bx bx-history me-1"></i> Riwayat Pembayaran
                                </h6>
                                <div class="table-responsive scroll-riwayat">
                                    <table class="table table-sm table-hover table-bordered align-middle">
                                        <thead class="table-light text-center sticky-top">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>Tgl Bayar</th>
                                                <th class="text-end">Nominal</th>
                                                <th>Metode</th>
                                                <th>Keterangan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($item->detail as $k => $d)
                                                <tr>
                                                    <td class="text-center">{{ $k + 1 }}</td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($d->tgl_bayar)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="text-end fw-semibold">
                                                        Rp {{ number_format($d->nominal_bayar, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-secondary">{{ ucfirst($d->metode_bayar) }}</span>
                                                    </td>
                                                    <td>{{ $d->keterangan ?? '-' }}</td>
                                                    <td class="text-center">
                                                        <form method="POST"
                                                            action="{{ route('tagihan_sppdata.destroy', $d->id_tagihan) }}"
                                                            class="formDelete d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="Hapus" data-bs-dismiss="modal">
                                                                <i class="bx bx-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        <i class="bx bx-info-circle"></i> Belum ada pembayaran untuk
                                                        bulan ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">
                    <i class="bx bx-x-circle me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- CSS Scroll --}}
<style>
    /* Scroll khusus riwayat pembayaran */
    .scroll-riwayat {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Custom scrollbar (biar lebih rapih) */
    .scroll-riwayat::-webkit-scrollbar {
        width: 6px;
    }

    .scroll-riwayat::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 10px;
    }

    .scroll-riwayat::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
</style>

{{-- JS --}}
<script src="{{ asset('add-plugins/myscriptpost.js') }}"></script>
<script>
    // Fungsi format ribuan
    function formatRibuan(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Auto-format input nominal & hitung total
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
</script>
