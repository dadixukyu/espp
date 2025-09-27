<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/images/logo_smk.png') }}" type="image/png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />

    <title>{{ config('app.name') }}</title>

    <style>
        body {
            background: linear-gradient(135deg, rgba(4, 82, 216, 0.7), rgba(0, 175, 233, 0.7)),
                url("{{ asset('assets/images/contoh.jpg') }}") center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 2rem;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            max-width: 420px;
            width: 100%;
            padding: 2.5rem;
            animation: fadeInUp 0.8s ease-in-out;
            position: relative;
        }

        .login-card h1 {
            color: #0452d8;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .login-card p {
            color: #555;
        }

        .form-control,
        .form-select {
            border-radius: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 10px rgba(4, 82, 216, 0.4);
            border-color: #0452d8;
        }

        .btn-primary {
            border-radius: 50px;
            font-weight: bold;
            background: linear-gradient(45deg, #0452d8, #00afeb);
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(4, 82, 216, 0.5);
        }

        .input-group-text {
            background: #f1f1f1;
            border-radius: 1rem 0 0 1rem;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Icon show/hide password */
        #show_hide_password i {
            cursor: pointer;
            transition: color 0.3s;
        }

        #show_hide_password i:hover {
            color: #0452d8;
        }
    </style>
</head>

<body>
    <div class="login-card text-center">
        <div class="mb-4">
            <img src="assets/images/logo_smk.png" alt="Logo" style="max-width: 100px;" class="mb-2">
            <h1>E-SPP</h1>
            <p class="text-muted">Silakan login untuk melanjutkan</p>
        </div>

        <form action="{{ url('login/proses') }}" method="POST">
            @csrf
            <div class="mb-3 text-start">
                <label for="email" class="form-label fw-bold">User Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                    <input type="text" class="form-control" id="email" name="email"
                        placeholder="Masukkan username" required value="{{ old('email') }}">
                </div>
            </div>

            <div class="mb-3 text-start">
                <label for="password" class="form-label fw-bold">Password</label>
                <div class="input-group" id="show_hide_password">
                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                    <input type="password" class="form-control border-end-0" id="password" name="password"
                        placeholder="Masukkan password" required>
                    <span class="input-group-text bg-transparent"><i class='bx bx-hide'></i></span>
                </div>
            </div>

            <div class="mb-3 text-start">
                <label for="tahun" class="form-label fw-bold">Tahun</label>
                <select class="form-select" id="tahun" name="tahun" required>
                    <option value="" selected>Pilih Tahun...</option>
                    <?php
                    $curYear = date('Y');
                    foreach (range($curYear, $curYear - 4) as $year) {
                        echo "<option value='$year'>$year</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-check form-switch mb-4 text-start">
                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bx bxs-lock-open"></i> Login
            </button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#show_hide_password span:last").on('click', function(event) {
                event.preventDefault();
                let input = $('#show_hide_password input');
                let icon = $('#show_hide_password i');
                if (input.attr("type") === "text") {
                    input.attr('type', 'password');
                    icon.addClass("bx-hide").removeClass("bx-show");
                } else {
                    input.attr('type', 'text');
                    icon.removeClass("bx-hide").addClass("bx-show");
                }
            });
        });
    </script>

    @if (session('login_error'))
        <script>
            Lobibox.notify('warning', {
                pauseDelayOnHover: true,
                delay: 3000,
                position: 'center top',
                icon: 'bx bx-error',
                msg: '{{ session('login_error') }}'
            });
        </script>
    @endif
</body>

</html>
