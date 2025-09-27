<!-- Rekap Jumlah Siswa Aktif Per Kelas -->
<div class="alert alert-info mb-3">
    <div class="d-flex justify-content-between flex-wrap">
        <div>
            <i class="bx bx-group"></i>
            Jumlah siswa <strong>aktif</strong> di
            <strong>Kelas {{ $kelas ?? '-' }}</strong> :
            <span class="badge bg-primary">{{ $jumlahSiswa ?? 0 }}</span>
        </div>
        <div>
            📊 Rekap siswa aktif per kelas:
            @if (!empty($rekapPerKelas) && is_array($rekapPerKelas))
                @foreach ($rekapPerKelas as $k => $total)
                    <span class="badge mx-1" style="background:#f1f1f1;color:#333;border-radius:6px;padding:6px 8px;">
                        {{ $k }}: <strong>{{ $total }}</strong>
                    </span>
                @endforeach
            @else
                <span class="text-muted">(Belum ada data rekap)</span>
            @endif
        </div>
    </div>
</div>

@php
    // Tentukan kelas tujuan otomatis
    $nextClass = ['X' => 'XI', 'XI' => 'XII', 'XII' => null];
    $kelasTujuan = $nextClass[$kelas] ?? null;
@endphp

<form id="formPindahKelas">
    @csrf

    @if ($kelasTujuan)
        <!-- Pilih Kelas Tujuan -->
        <div class="row mt-3 mb-3">
            <div class="col-md-4">
                <select name="kelas_tujuan" class="form-select" required>
                    <option value="{{ $kelasTujuan }}">Kelas {{ $kelasTujuan }}</option>
                </select>
            </div>
        @else
            <!-- Jika kelas XII -->
            <div class="alert alert-warning">
                Siswa kelas XII bila <strong>dipindahkan</strong> akan otomatis menjadi <strong>Lulus</strong>.
            </div>
    @endif

    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-success" onclick="myReloadTable1()">
            <i class="bx bx-transfer-alt"></i> Pindahkan
        </button>
    </div>

    <!-- ✅ Tabel masuk dalam form -->
    <div class="table-responsive mt-3">
        <table id="tblPindahKelas" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
            style="width:100%">
            <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
                <tr>
                    <th class="text-center"><input type="checkbox" id="checkAll"></th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas Saat Ini</th>
                    <th>Jurusan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $item)
                    <tr>
                        <td class="text-center align-top">
                            <input type="checkbox" name="id_siswa[]" value="{{ $item->id_siswa }}">
                        </td>
                        <td>{{ $item->nisn }}</td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td class="text-center">{{ $item->kelas }}</td>
                        <td class="text-center">{{ $item->jurusan }}</td>
                        <td class="text-center">
                            @if ($item->status_siswa == 'aktif')
                                <span class="badge bg-success"><i class="bx bx-check-circle"></i> Aktif</span>
                            @elseif ($item->status_siswa == 'lulus')
                                <span class="badge bg-primary"><i class="bx bx-award"></i> Lulus</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>

<script>
    $(document).ready(function() {
        initTable();

        // Checkbox select all
        $(document).on('change', '#checkAll', function() {
            $('input[name="id_siswa[]"]').prop('checked', $(this).is(':checked'));
        });
    });

    function initTable() {
        $('#tblPindahKelas').DataTable({
            destroy: true,
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikut",
                    previous: "Sebelumnya"
                },
                zeroRecords: "Data tidak ditemukan"
            }
        });
    }

    // Fungsi pindah kelas / lulus
    function myReloadTable1() {
        let form = $('#formPindahKelas');
        let checked = $('input[name="id_siswa[]"]:checked').length;

        if (checked === 0) {
            Swal.fire('Oops!', 'Pilih siswa yang akan dipindahkan!', 'warning');
            return;
        }

        $.ajax({
            url: "{{ url('pindahkelasdata/pindahkan') }}",
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#loading-spinner').removeClass('d-none');
                $('.viewTable').hide();
            },
            success: function(response) {
                if (response.success) {
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        delay: 3000,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: response.success
                    });

                    if (response.myReload === 'pindahkelasdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }
                } else if (response.error) {
                    Swal.fire('Gagal', response.error, 'error');
                }
            },
            complete: function() {
                $('#loading-spinner').addClass('d-none');
                $('.viewTable').fadeIn();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: `Status: ${xhr.status} <br> Pesan: ${thrownError}`,
                    confirmButtonText: 'Tutup'
                });
            }
        });
    }
</script>
