<!-- Rekap Status Siswa -->
<div class="alert alert-info mb-3">
    <div class="d-flex justify-content-start flex-wrap gap-2">
        🏷️ Rekap status siswa:
        @foreach (['aktif' => 'success', 'lulus' => 'primary', 'pindah' => 'warning', 'keluar' => 'danger'] as $statusKey => $color)
            <span class="badge bg-{{ $color }} mx-1">
                {{ ucfirst($statusKey) }}: <strong>{{ $rekapPerStatus[$statusKey] ?? 0 }}</strong>
            </span>
        @endforeach
    </div>
</div>

<!-- Form Ubah Status Menjadi Aktif -->
<form id="formAktifkanSiswa">
    @csrf

    <div class="mt-3">
        <button type="button" class="btn btn-success" onclick="myReloadTable1()">
            <i class="bx bx-check-circle"></i> Jadikan Aktif
        </button>
    </div>

    <!-- Tabel Siswa Sesuai Status -->
    <div class="table-responsive mt-3">
        <table id="tblSiswaStatus" class="table table-bordered table-hover align-middle nowrap shadow-sm rounded"
            style="width:100%">
            <thead class="text-white fw-bold" style="background: linear-gradient(90deg, #0d6efd, #198754);">
                <tr>
                    <th class="text-center"><input type="checkbox" id="checkAllStatus"></th>
                    <th>No.</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Tahun Masuk</th>
                    <th>Jurusan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $item)
                    @if (in_array($item->status_siswa, ['lulus', 'pindah', 'keluar']))
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="id_siswa[]" value="{{ $item->id_siswa }}">
                            </td>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nisn }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td class="text-center">{{ $item->tahun_masuk }}</td>
                            <td class="text-center">{{ $item->jurusan }}</td>
                            <td class="text-center">
                                @php
                                    $statusColor = [
                                        'aktif' => 'success',
                                        'lulus' => 'primary',
                                        'pindah' => 'warning',
                                        'keluar' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColor[$item->status_siswa] ?? 'secondary' }}">
                                    @if ($item->status_siswa == 'lulus')
                                        <i class="bx bx-award"></i> Lulus
                                    @elseif($item->status_siswa == 'pindah')
                                        <i class="bx bx-transfer"></i> Pindah
                                    @elseif($item->status_siswa == 'keluar')
                                        <i class="bx bx-x-circle"></i> Keluar
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>


</form>

<!-- Script JS -->
<script>
    $(document).ready(function() {
        // Init DataTable
        $('#tblSiswaStatus').DataTable({
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

        // Checkbox Select All
        $(document).on('change', '#checkAllStatus', function() {
            $('input[name="id_siswa[]"]').prop('checked', $(this).is(':checked'));
        });
    });

    // Fungsi ubah status siswa menjadi aktif
    function myReloadTable1() {
        let form = $('#formAktifkanSiswa');
        let checked = $('input[name="id_siswa[]"]:checked').length;

        if (checked === 0) {
            Swal.fire('Oops!', 'Pilih siswa yang akan dijadikan aktif!', 'warning');
            return;
        }

        $.ajax({
            url: "{{ url('penampungdata/ubahStatusAktif') }}",
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

                    if (response.myReload === 'penampungdata') {
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
