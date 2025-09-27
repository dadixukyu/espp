<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="bx bx-edit-alt me-2"></i>Lengkapi Data Siswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form action="{{ route('siswadata.update', $row->id_siswa) }}" class="formData" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                    <div class="p-3 border rounded shadow-sm bg-light">

                        <h5 class="mb-3 text-primary"><i class="bx bx-edit-alt me-1"></i>
                            {{ $title_form ?? 'FORM LENGKAPI DATA SISWA' }}</h5>
                        <hr class="mb-4" />

                        <!-- Tahun Ajaran -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bx bx-calendar me-1 text-primary"></i> Tahun
                                Ajaran</label>
                            <div class="card border-primary shadow-sm">
                                <div class="card-body p-2">
                                    @if ($tahunAjaran->count() > 0)
                                        <span class="fw-bold text-primary">{{ $tahunAjaran->first()->nama_ta }}</span>
                                        <input type="hidden" name="id_tahun"
                                            value="{{ $tahunAjaran->first()->id_tahun }}">
                                    @else
                                        <span class="text-muted fst-italic">Belum ada data tahun ajaran aktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- KOLOM KIRI -->
                            <div class="col-md-6">
                                <h6 class="text-secondary fw-bold"><i class="bx bx-user me-1"></i> Data Pribadi</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-id-card me-1 text-primary"></i>
                                        NISN</label>
                                    <input type="text" name="nisn" class="form-control"
                                        value="{{ $row->nisn }}" placeholder="Masukkan NISN">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i
                                            class="bx bx-user-circle me-1 text-primary"></i> Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control"
                                        value="{{ $row->nama_lengkap }}" placeholder="Masukkan nama lengkap">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-user me-1 text-primary"></i> Jenis
                                        Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control single-select">
                                        <option value="">Pilih...</option>
                                        <option value="L" {{ $row->jenis_kelamin == 'L' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="P" {{ $row->jenis_kelamin == 'P' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-map-pin me-1 text-primary"></i>
                                        Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control"
                                        value="{{ $row->tempat_lahir }}" placeholder="Masukkan tempat lahir">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i
                                            class="bx bx-calendar-event me-1 text-primary"></i> Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control"
                                        value="{{ $row->tanggal_lahir }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-book-open me-1 text-primary"></i>
                                        Agama</label>
                                    <input type="text" name="agama" class="form-control"
                                        value="{{ $row->agama }}" placeholder="Masukkan agama">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-home me-1 text-primary"></i>
                                        Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat">{{ $row->alamat }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-envelope me-1 text-primary"></i>
                                        Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ $row->email }}" placeholder="Masukkan email siswa">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-phone me-1 text-primary"></i> No
                                        HP Siswa</label>
                                    <input type="text" name="no_hp" class="form-control"
                                        value="{{ $row->no_hp }}" placeholder="Masukkan no hp siswa">
                                </div>

                                <!-- Kategori Biaya SPP -->
                                <h6 class="text-secondary fw-bold mt-4">
                                    <i class="bx bx-credit-card me-1"></i> Kategori Biaya SPP
                                </h6>
                                <div class="mb-3">
                                    <div class="card border-primary shadow-sm">
                                        <div class="card-body p-3">
                                            @if ($parSpp)
                                                <div class="d-flex justify-content-between border-bottom py-1">
                                                    <span>Tahun</span>
                                                    <span class="text-primary">{{ $parSpp->tahun }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-1">
                                                    <span>Nominal</span>
                                                    <span class="fw-bold text-primary">
                                                        Rp {{ number_format($parSpp->nominal, 2, ',', '.') }}
                                                    </span>
                                                </div>
                                                <input type="hidden" name="kategori_biaya"
                                                    value="{{ $parSpp->tahun }}">
                                            @else
                                                <div class="text-muted fst-italic">Belum ada data SPP</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-money me-1 text-success"></i>
                                        Pengurangan Biaya SPP (Rp)</label>
                                    <input type="text" id="pengurangan_biaya" name="pengurangan_biaya"
                                        class="form-control"
                                        value="{{ number_format($row->pengurangan_biaya ?? 0, 0, ',', '.') }}"
                                        placeholder="Nominal Pengurangan SPP (Rp)">
                                </div>

                                <!-- Biaya Lainnya -->
                                <h6 class="text-secondary fw-bold mt-4">
                                    <i class="bx bx-wallet me-1"></i> Biaya Lainnya
                                </h6>
                                <div class="mb-3">
                                    <div class="card border-primary shadow-sm p-3">
                                        @if ($parBiaya && $parBiaya->count() > 0)
                                            @foreach ($parBiaya as $biaya)
                                                <div class="d-flex justify-content-between border-bottom py-1">
                                                    <span>{{ $biaya->nama_biaya }}</span>
                                                    <span class="fw-bold text-primary">
                                                        Rp {{ number_format($biaya->nominal, 2, ',', '.') }}
                                                    </span>
                                                </div>
                                                <input type="hidden" name="biaya_lain[]"
                                                    value="{{ $biaya->tahun }}">
                                            @endforeach
                                        @else
                                            <div class="text-muted fst-italic">Belum ada data biaya lain</div>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <!-- KOLOM KANAN -->
                            <div class="col-md-6">
                                <h6 class="text-secondary fw-bold d-flex align-items-center">
                                    <span class="me-2"><i class="bx bx-graduation text-secondary"></i><i
                                            class="bx bx-book-open text-secondary"></i></span>
                                    Data Akademik
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-calendar me-1 text-primary"></i>
                                        Tahun Masuk</label>
                                    <input type="number" name="tahun_masuk" class="form-control"
                                        value="{{ $row->tahun_masuk }}" min="2000" max="2099"
                                        placeholder="Masukkan tahun masuk">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i
                                            class="bx bx-building-house me-1 text-primary"></i> Kelas</label>
                                    <select name="kelas" class="form-control single-select">
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($parKelas as $kelas)
                                            <option value="{{ $kelas->id_kelas }}"
                                                {{ $row->kelas == $kelas->kelas ? 'selected' : '' }}>
                                                {{ $kelas->kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i
                                            class="bx bx-briefcase-alt me-1 text-primary"></i> Jurusan</label>
                                    <select name="jurusan" class="form-control single-select">
                                        <option value="">Pilih Jurusan</option>
                                        @foreach ($parJurusan as $jurusan)
                                            <option value="{{ $jurusan->id_kelas }}"
                                                {{ $row->jurusan == $jurusan->jurusan ? 'selected' : '' }}>
                                                {{ $jurusan->jurusan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i
                                            class="bx bx-user-check me-1 text-primary"></i>
                                        Status Siswa</label>
                                    <select name="status_siswa" class="form-control single-select">
                                        <option value="aktif" {{ $row->status_siswa == 'aktif' ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="lulus" {{ $row->status_siswa == 'lulus' ? 'selected' : '' }}>
                                            Lulus</option>
                                        <option value="pindah" {{ $row->status_siswa == 'pindah' ? 'selected' : '' }}>
                                            Pindah</option>
                                        <option value="keluar" {{ $row->status_siswa == 'keluar' ? 'selected' : '' }}>
                                            Keluar</option>
                                    </select>
                                </div>

                                <!-- Data Orang Tua / Wali -->
                                <h6 class="text-secondary fw-bold mt-4"><i class="bx bx-user-pin me-1"></i> Data Orang
                                    Tua / Wali</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-male me-1 text-primary"></i>
                                        Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control"
                                        value="{{ $row->nama_ayah }}" placeholder="Masukkan nama ayah">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i
                                            class="bx bx-briefcase me-1 text-primary"></i>
                                        Pekerjaan Ayah</label>
                                    <input type="text" name="pekerjaan_ayah" class="form-control"
                                        value="{{ $row->pekerjaan_ayah }}" placeholder="Masukkan pekerjaan ayah">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-female me-1 text-primary"></i>
                                        Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control"
                                        value="{{ $row->nama_ibu }}" placeholder="Masukkan nama ibu">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i
                                            class="bx bx-briefcase me-1 text-primary"></i>
                                        Pekerjaan Ibu</label>
                                    <input type="text" name="pekerjaan_ibu" class="form-control"
                                        value="{{ $row->pekerjaan_ibu }}" placeholder="Masukkan pekerjaan ibu">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-user me-1 text-primary"></i>
                                        Nama Wali</label>
                                    <input type="text" name="nama_wali" class="form-control"
                                        value="{{ $row->nama_wali }}" placeholder="Masukkan nama wali">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i
                                            class="bx bx-phone-call me-1 text-primary"></i>
                                        No HP Wali</label>
                                    <input type="text" name="no_hp_wali" class="form-control"
                                        value="{{ $row->no_hp_wali }}" placeholder="Masukkan no hp wali">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bx bx-home-alt me-1 text-primary"></i>
                                        Alamat Wali</label>
                                    <textarea name="alamat_wali" class="form-control" rows="2" placeholder="Masukkan alamat wali">{{ $row->alamat_wali }}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> TUTUP
                    </button>
                    <button type="reset" class="btn btn-secondary btn-sm">
                        <i class="bx bx-reset me-1"></i> RESET
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" id="tombolSave">
                        <i class="bx bx-save me-1"></i> SIMPAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Plugins JS -->
<script src="{{ asset('add-plugins/myscript.js') }}"></script>
<script src="{{ asset('add-plugins/myscriptpost.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script>
    $('.single-select').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Pilih opsi...',
        allowClear: true,
        dropdownParent: $('#modalFormData')
    });

    $(document).on('input', '#pengurangan_biaya', function() {
        let raw = this.value.replace(/[^0-9,]/g, '');
        const endsWithComma = raw.endsWith(',');
        const [integerPart, decimalPart = ''] = raw.split(',');
        const formatted = formatRibuan(integerPart);
        this.value = 'Rp ' + formatted + (decimalPart || endsWithComma ? ',' + decimalPart : '');
        this.setSelectionRange(this.value.length, this.value.length);
    });

    function formatRibuan(angka) {
        if (!angka) return '0';
        angka = angka.replace(/^0+/, '') || '0';
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
</script>
