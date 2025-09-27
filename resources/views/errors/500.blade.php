<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/logo_prov.png') }}" type="image/png" />
    <!--plugins-->
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css" rel="stylesheet') }}" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css" rel="stylesheet') }}">
    <link href="{{ asset('assets/css/icons.css" rel="stylesheet') }}">
    <title>{{ config('app.name') }}</title>
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">
        <header class="shadow pt-5">
            <nav class="navbar navbar-expand-lg navbar-light bg-white rounded fixed-top rounded-0 shadow-sm">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        {{-- <img src="{{ asset('assets/images/logo_qr.png') }}" width="400" alt="" /> --}}
                    </a>
                    {{-- <div>
                        <a href="{{ route('main') }}" class="btn btn-sm btn-primary" style="background-color: #8b0000;color: #fff;" id="tbl_kembali">
                            <i class='bx bx-arrow-back mr-25'></i> Kembali
                        </a>
                    </div> --}}
                </div>
            </nav>
        </header>
        <div class="page-content pt-5">
            <div class="error-404 d-flex align-items-center justify-content-center">
                <div class="container">
                    <div class="card py-5">
                        <div class="row g-0">
                            <div class="col-xl-5">
                                <div class="card-body p-4">
                                    <h1 class="display-1"><span class="text-warning">5</span><span
                                            class="text-danger">0</span><span class="text-primary">0</span></h1>
                                    <h2 class="font-weight-bold display-4">Sorry, unexpected error</h2>
                                    <p>Looks like you are lost!
                                        {{-- <br>May be you are not connected to the internet! --}}
                                    </p>
                                    {{-- <div class="mt-5"> <a href="javascript:;"
                                            class="btn btn-lg btn-primary px-md-5 radius-30">Go Home</a>
                                        <a href="javascript:;"
                                            class="btn btn-lg btn-outline-dark ms-3 px-md-5 radius-30">Back</a>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-xl-7">
                                <img src="{{ asset('assets/images/errors-images/505-error.png') }}" class="img-fluid"
                                    alt="">
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-1 fixed-bottom border-top shadow">
            <div class="d-flex justify-content-center flex-wrap">
                {{-- <ul class="list-inline mb-0">
					<li class="list-inline-item">Follow Us :</li>
					<li class="list-inline-item"><a href="javascript:;"><i class='bx bxl-facebook me-1'></i>Facebook</a>
					</li>
					<li class="list-inline-item"><a href="javascript:;"><i class='bx bxl-twitter me-1'></i>Twitter</a>
					</li>
					<li class="list-inline-item"><a href="javascript:;"><i class='bx bxl-google me-1'></i>Google</a>
					</li>
				</ul> --}}
                <p class="mb-0">Copyright © 2025. SMK GAJAH MADA PALEMBANG</p>

            </div>
        </div>
    </div>
    <!-- end wrapper -->
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>


</html>
