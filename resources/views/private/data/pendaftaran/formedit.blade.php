<div class="modal fade" id="modalFormData" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER MODAL -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bx bx-user-plus me-2"></i> Form Edit Pendaftaran Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- FORM START -->
            <form action="{{ route('pendaftarandata.update', $row->id_pendaftaran) }}" class="formData" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="id_siswa" value="{{ $row->siswa->id_siswa ?? '' }}">

                {{-- isi field lain di sini --}}
                <!-- BODY MODAL -->
                <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                    <div class="p-3 border rounded shadow-sm bg-light">

                        <!-- Judul Form -->
                        <h5 class="mb-3 text-primary">
                            <i class="bx bx-edit-alt me-1"></i>
                            {{ $title_form ?? 'FORM INPUT DATA PENDAFTARAN' }}
                        </h5>
                        <hr class="mb-4" />

                        <!-- DATA PRIBADI -->
                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="bx bx-user-circle me-1"></i> Data Pribadi
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-id-card me-1 text-primary"></i> NISN
                                </label>
                                <input type="text" name="nisn" class="form-control" value="{{ $row->nisn }}"
                                    placeholder="Masukkan NISN">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-user-circle me-1 text-primary"></i> Nama Calon Siswa
                                </label>
                                <input type="text" name="nama_lengkap" class="form-control"
                                    value="{{ $row->nama_lengkap }}" placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-user me-1 text-primary"></i> Jenis Kelamin
                                </label>
                                <select name="jenis_kelamin" class="form-control single-select">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ $row->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P" {{ $row->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-map-pin me-1 text-primary"></i> Tempat Lahir
                                </label>
                                <input type="text" name="tempat_lahir" class="form-control"
                                    value="{{ $row->tempat_lahir }}" placeholder="Masukkan tempat lahir">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-calendar-event me-1 text-primary"></i> Tanggal Lahir
                                </label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    value="{{ $row->tanggal_lahir }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-book-open me-1 text-primary"></i> Agama
                                </label>
                                <input type="text" name="agama" class="form-control" value="{{ $row->agama }}"
                                    placeholder="Masukkan agama">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-home me-1 text-primary"></i> Alamat
                                </label>
                                <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat">{{ $row->alamat }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4" />

                        <!-- DATA AKADEMIK -->
                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="bx bx-book-content me-1"></i> Data Akademik
                        </h6>
                        <label class="form-label fw-bold">
                            <i class="form-label fw-bold text-primary"></i> Tahun Ajaran
                        </label>
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

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bx bx-calendar me-1 text-primary"></i> Tahun Masuk
                            </label>
                            <input type="number" name="tahun_masuk" class="form-control"
                                value="{{ $row->tahun_masuk }}" min="2000" max="2099"
                                placeholder="Masukkan tahun masuk">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bx bx-calendar me-1 text-primary"></i> Asal Sekolah
                            </label>
                            <input type="text" name="asal_sekolah" class="form-control"
                                value="{{ $row->asal_sekolah }}" placeholder="Masukkan Asal Sekolah">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bx bx-user-check me-1 text-primary"></i> Status Siswa
                            </label>
                            <select name="status_siswa" class="form-control single-select">
                                <option value="aktif" {{ $row->status_siswa == 'aktif' ? 'selected' : '' }} selected>
                                    Aktif</option>
                                {{-- <option value="lulus">Lulus</option>
                                <option value="pindah">Pindah</option>
                                <option value="keluar">Keluar</option> --}}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bx bx-building-house me-1 text-primary"></i>
                                Kelas</label>
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
                            <label class="form-label fw-bold"><i class="bx bx-briefcase-alt me-1 text-primary"></i>
                                Jurusan</label>
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

                        <hr class="my-4" />

                        <!-- DATA BIAYA -->
                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="bx bx-wallet me-1"></i> Data Biaya
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Kategori Biaya SPP</label>
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
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="bx bx-money me-1 text-success"></i>
                                    Pengurangan Biaya SPP (Rp)</label>
                                <input type="text" id="pengurangan_biaya" name="pengurangan_biaya"
                                    class="form-control"
                                    value="{{ 'Rp ' . number_format($row->pengurangan_biaya ?? 0, 0, ',', '.') }}"
                                    placeholder="Nominal Pengurangan SPP (Rp)">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Biaya Lainnya</label>
                                <div class="card border-primary shadow-sm">
                                    <div class="card-body p-3">
                                        @foreach ($parBiaya as $biaya)
                                            <div class="d-flex justify-content-between border-bottom py-1">
                                                <span>{{ $biaya->nama_biaya }}</span>
                                                <span class="fw-bold text-primary">
                                                    Rp {{ number_format($biaya->nominal, 2, ',', '.') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @foreach ($parBiaya as $biaya)
                                    <input type="hidden" name="biaya_lain[]" value="{{ $biaya->tahun }}">
                                @endforeach
                            </div>
                            {{-- <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="bx bx-money me-1 text-success"></i>
                                    Biaya Pendaftaran(Rp)</label>
                                <input type="text" id="biaya_pendaftaran" name="biaya_pendaftaran"
                                    class="form-control"
                                    value="{{ 'Rp ' . number_format($row->biaya_pendaftaran ?? 0, 0, ',', '.') }}"
                                    placeholder="Nominal Pengurangan SPP (Rp)">
                            </div> --}}
                        </div>

                        <hr class="my-4" />

                        <!-- KONTAK -->
                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="bx bx-phone me-1"></i> Kontak
                        </h6>
                        <div class="row g-3">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-phone me-1 text-primary"></i> No HP Siswa
                                </label>
                                <input type="text" name="no_hp" class="form-control"
                                    value="{{ $row->no_hp }}" placeholder="Masukkan no hp siswa">
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-envelope me-1 text-primary"></i> Email
                                </label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $row->email }}" placeholder="Masukkan email siswa">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-calendar-event me-1 text-primary"></i> Tanggal Daftar
                                </label>
                                <input type="date" name="tgl_daftar" class="form-control"
                                    value="{{ $row->tgl_daftar }}">
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
                    <button type="submit" class="btn btn-primary btn-sm" id="tombolSave">
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
    $('.single-select').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Pilih opsi...',
        allowClear: true,
        dropdownParent: $('#modalFormData')
    });

    // Format uang otomatis
    function formatRibuan(angka) {
        if (!angka) return '0';
        angka = angka.replace(/^0+/, '') || '0';
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    $(document).on('input', '#pengurangan_biaya, #biaya_pendaftaran', function() {
        let raw = this.value.replace(/[^0-9,]/g, '');
        const endsWithComma = raw.endsWith(',');
        const [integerPart, decimalPart = ''] = raw.split(',');
        const formatted = formatRibuan(integerPart);
        this.value = 'Rp ' + formatted + (decimalPart || endsWithComma ? ',' + decimalPart : '');
        this.setSelectionRange(this.value.length, this.value.length);
    });
</script>
