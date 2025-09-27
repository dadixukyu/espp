<div class="modal fade" id="modalFormData" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg rounded-3 border-0">

            {{-- Modal Header --}}
            <div class="modal-header bg-gradient bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="bx bx-receipt fs-4 me-2"></i> Detail Tagihan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body">
                @if ($result->isEmpty())
                    <div class="alert alert-info text-center mb-0">
                        <i class="bx bx-info-circle"></i> Belum ada data tagihan untuk pendaftaran ini.
                    </div>
                @else
                    @foreach ($result as $item)
                        <div class="card mb-3 shadow-sm border-0">
                            <div
                                class="card-header bg-light rounded-top d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">
                                        <i class="bx bx-book-open me-1"></i> {{ $item->biaya->nama_biaya ?? '-' }}
                                    </h6>
                                    <div class="small text-muted">
                                        <i class="bx bx-money me-1"></i> Tagihan:
                                        <span class="fw-semibold text-dark">
                                            Rp {{ number_format($item->tagihan, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">
                                        Sisa:
                                        <span class="text-danger">
                                            Rp {{ number_format($item->sisa_tagihan, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <span
                                        class="badge px-3 py-2 rounded-pill 
                                        @if (strtolower($item->status) == 'lunas') bg-success
                                        @elseif(strtolower($item->status) == 'cicilan') bg-warning text-dark
                                        @else bg-danger @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bx bx-history me-1"></i> Riwayat Pembayaran
                                </h6>

                                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                    <table class="table table-sm table-hover table-bordered align-middle mb-0">
                                        <thead class="table-light text-center sticky-top">
                                            <tr>
                                                <th>#</th>
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
                                                        {{ \Carbon\Carbon::parse($d->tgl_bayar)->format('d/m/Y') }}</td>
                                                    <td class="text-end fw-semibold">
                                                        Rp {{ number_format($d->nominal_bayar, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary">{{ $d->metode_bayar }}</span>
                                                    </td>
                                                    <td>{{ $d->keterangan }}</td>
                                                    <td class="text-center">
                                                        <form method="POST"
                                                            action="{{ route('tagihanlaindata.destroy', $d->id_detail) }}"
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
                                                        <i class="bx bx-info-circle"></i> Belum ada pembayaran
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

{{-- JS --}}
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
</script>
