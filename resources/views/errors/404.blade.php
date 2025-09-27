<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/images/logo_prov.png') }}" type="image/png" />

    <!-- Bootstrap & Fonts -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <title>{{ config('app.name') }} - Halaman Tidak Ditemukan</title>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e0e7ff);
        }

        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            animation: fadeInUp 1s ease forwards;
            display: flex;
            max-width: 900px;
            background: #fff;
        }

        .error-left,
        .error-right {
            padding: 50px;
        }

        .error-left h1 {
            font-size: 120px;
            font-weight: 900;
            line-height: 1;
            animation: pulse 2s infinite;
        }

        .error-left h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .error-left p {
            font-size: 16px;
            color: #6c757d;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 40px;
            font-weight: 600;
            border-radius: 50px;
            background-color: #4e73df;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #2e59d9;
            transform: scale(1.05);
        }

        .error-right img {
            max-width: 100%;
            animation: float 4s ease-in-out infinite;
        }

        /* Animations */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                color: #4e73df;
            }

            50% {
                transform: scale(1.1);
                color: #1cc88a;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        footer {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            padding: 10px 0;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .error-card {
                flex-direction: column;
                text-align: center;
            }

            .error-left,
            .error-right {
                padding: 30px;
            }

            .error-left h1 {
                font-size: 90px;
            }

            .error-left h2 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-left">
                <h1>4<span class="text-danger">0</span>4</h1>
                <h2>Halaman Tidak Ditemukan</h2>
                <p>Maaf, halaman yang Anda cari tidak tersedia. Silakan kembali ke halaman utama untuk melanjutkan.</p>
                <a href="{{ route('main') }}" class="btn-back">Kembali ke Beranda</a>
            </div>
            <div class="error-right">
                <img src="https://cdn.searchenginejournal.com/wp-content/uploads/2019/03/shutterstock_1338315902.png"
                    alt="404 Illustration">
            </div>
        </div>
    </div>

    <footer>
        Copyright © 2025. SMK GAJAH MADA PALEMBANG
    </footer>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
