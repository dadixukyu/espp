<!doctype html>
<html lang="en" class=''>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/logo_smk.png') }}" type="image/png" />
    <!--plugins-->
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <link href="{{ asset('assets/plugins/datetimepicker/css/classic.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datetimepicker/css/classic.time.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datetimepicker/css/classic.date.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css') }}">
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/fancy-file-uploader/fancy_fileupload.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css') }}" rel="stylesheet" />

    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    <!-- CSS untuk Gallery Lightbox-->
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.min.css') }}">

    <!-- CSS untuk Gallery fancybox-->
    <link rel="stylesheet" href="{{ asset('assets/galeri/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/galeri/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/galeri/css/responsive.css') }}">


    <title>{{ config('app.name') }}</title>

    <style>
        html,
        body {
            zoom: 100%;
        }

        /* CSS SPINNER */
        #loading-spinner {
            align-items: center;
            background: radial-gradient(rgba(20, 20, 20, .1), rgba(0, 0, 0, .1));
            /* .1 untuk mengatur opacity background */
            display: flex;
            height: 100vh;
            justify-content: center;
            left: 0;
            position: fixed;
            top: 0;
            transition: opacity 0.3s linear;
            width: 100%;
            height: 100%;
            z-index: 9999;
        }

        .loading-text {
            font-weight: bold;
            font-size: 17px;
        }

        .loader {
            width: 30px;
            aspect-ratio: 1;
            background: #554236;
            display: grid;
            transform-origin: top right;
            animation: l6-0 1s infinite linear;
        }

        .loader::before,
        .loader::after {
            content: "";
            grid-area: 1/1;
            background: #f77825;
            transform-origin: inherit;
            animation: inherit;
            animation-name: l6-1;
        }

        .loader::after {
            background: #20B2AA;
            /*#60B99A*/
            --s: 180deg;
        }

        @keyframes l6-0 {

            70%,
            100% {
                transform: rotate(360deg)
            }
        }

        @keyframes l6-1 {
            30% {
                transform: rotate(var(--s, 90deg))
            }

            70% {
                transform: rotate(0)
            }
        }

        /* END CSS SPINNER */

        /* CSS input file dadix */
        .dadix-file-button input[type=file] {
            margin-left: -2px !important;
        }

        .dadix-file-button input[type=file]::-webkit-file-upload-button {
            display: none;
        }

        .dadix-file-button input[type=file]::file-selector-button {
            display: none;
        }

        .dadix-file-button:hover label {
            background-color: #dde0e3;
            cursor: pointer;
        }

        /* END CSS input fiile dadix */
    </style>
    <!-- Spinner -->
    <div id="loading-spinner" class="d-none">
        <div class="d-flex align-items-center">
            <!-- <div class="spinner-border text-primary mr-2" role="status"></div> -->
            <div class="loader text-primary mr-2" role="status"></div>
            <span class="loading-text ms-1"><i>Loading......</i></span>
        </div>
    </div>
    <!-- End Spinner -->

</head>

<body class="">
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        @include('private/layout/sidebar')
        <!--end sidebar wrapper -->
        <!--start header -->
        @include('private/layout/header')
        <!--end header -->
        <!-- BEGIN: Content-->
        <div class="page-wrapper">
            <div class="page-content p-3">
                @yield('isi')
            </div>
        </div>
        <!-- END: Content-->

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <!-- BEGIN: Footer-->
        @include('private/layout/footer')
        <!-- END: Footer-->

    </div>
    <!--end wrapper-->
    <!--start switcher-->

    <!--end switcher-->
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.extension.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>

    <script src="{{ asset('add-plugins/myscript.js') }}"></script>
    <!--app JS-->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <!--notification js -->
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/notifications.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/notification-custom-script.js') }}"></script>

    <script src="{{ asset('assets/plugins/datetimepicker/js/legacy.js') }}"></script>
    <script src="{{ asset('assets/plugins/datetimepicker/js/picker.js') }}"></script>
    <script src="{{ asset('assets/plugins/datetimepicker/js/picker.time.js') }}"></script>
    <script src="{{ asset('assets/plugins/datetimepicker/js/picker.date.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js') }}"></script>
    <script
        src="{{ asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js') }}">
    </script>

    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/fancy-file-uploader/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancy-file-uploader/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancy-file-uploader/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js') }}"></script>


</body>

</html>
