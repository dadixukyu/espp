@extends('main')
@section('isi')
    @php
        $id = Auth::user()->id;
    @endphp

    <div class="content-body">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0"><i class="bx bx-user-circle mr-2"></i>{{ $title }}</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Bagian Username -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-info text-white">
                                <i class="bx bx-envelope mr-1"></i> Ubah Username
                            </div>
                            <div class="card-body">
                                <form action="{{ route('pengaturanakun.updateemail') }}" class="formDataPengaturanAkun"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                        <input type="text" class="form-control" name="email" id="email"
                                            placeholder="Masukkan Username Baru" required>
                                        <button type="submit" class="btn btn-info btn-sm" id="tombolSaveEmail">
                                            <i class="bx bx-send mr-1"></i> Ganti
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Bagian Password -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-white">
                                <i class="bx bx-lock-alt mr-1"></i> Ubah Password
                            </div>
                            <div class="card-body">
                                <form action="{{ route('pengaturanakun.updatepassword', $id) }}"
                                    class="formDataPengaturanAkun" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">

                                    @php
                                        $passwordFields = [
                                            [
                                                'label' => 'Password Lama',
                                                'name' => 'password_old',
                                                'id' => 'showpassword1',
                                            ],
                                            [
                                                'label' => 'Password Baru',
                                                'name' => 'password_new',
                                                'id' => 'showpassword2',
                                            ],
                                            [
                                                'label' => 'Konfirmasi Password',
                                                'name' => 'password_new_confirmation',
                                                'id' => 'showpassword3',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($passwordFields as $field)
                                        <div class="mb-3">
                                            <label class="form-label">{{ $field['label'] }}</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="{{ $field['name'] }}"
                                                    id="{{ $field['id'] }}" placeholder="{{ $field['label'] }}" required>
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="show('{{ $loop->iteration }}')">
                                                    <i class="eye{{ $loop->iteration }} bx bx-hide"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    <button type="submit" class="btn btn-warning btn-sm" id="tombolSave">
                                        <i class='bx bx-caret-right-circle mr-1'></i> Ganti Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Info -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <i class="bx bx-info-circle mr-1"></i> Info
                            </div>
                            <div class="card-body" style="text-align: justify;">
                                <ul>
                                    <li>Minimal 6 karakter.</li>
                                    <li>Gunakan kombinasi huruf besar & kecil.</li>
                                    <li>Gunakan simbol atau angka.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function show(kode) {
                var x = document.getElementById("showpassword" + kode);
                if (x.type === "password") {
                    x.type = "text";
                    $('.eye' + kode).removeClass("bx-hide").addClass("bx-show");
                } else {
                    x.type = "password";
                    $('.eye' + kode).removeClass("bx-show").addClass("bx-hide");
                }
            }

            $(document).ready(function() {
                var tombolSave, label;

                $("#tombolSave").click(function() {
                    tombolSave = 'tombolSave';
                    label = 'PASSWORD';
                });
                $("#tombolSaveEmail").click(function() {
                    tombolSave = 'tombolSaveEmail';
                    label = 'EMAIL';
                });

                $('.formDataPengaturanAkun').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: $(this).attr('method'),
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: "json",
                        beforeSend: function() {
                            $('#' + tombolSave).prop('disabled', true).html(
                                "<i class='mr-1 spinner-border spinner-border-sm'></i>");
                        },
                        complete: function() {
                            $('#' + tombolSave).prop('disabled', false).html(
                                "<i class='bx bx-caret-right-circle mr-1'></i> GANTI " + label);
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil', response.success, 'success').then(() => {
                                    if (response.myReload == 'ReloadPassword') window
                                        .location.href = response.route;
                                    $('#email').val('');
                                })
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status == 422) {
                                var errors = xhr.responseJSON.errors;
                                var errorList = '';
                                for (var key in errors) errorList += '\n - ' + errors[key] +
                                    '</br>';
                                Swal.fire('Gagal', errorList, 'warning');
                            } else {
                                Swal.fire('Error', xhr.status + '\n' + xhr.statusText, 'error');
                            }
                        }
                    });
                });
            });
        </script>
    @endsection
