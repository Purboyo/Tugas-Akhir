<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Form Laporan' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendor/focus-2/images/favicon.png') }}">

    <!-- Style -->
    <link rel="stylesheet" href="{{ asset('vendor/focus-2/vendor/owl-carousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/focus-2/vendor/owl-carousel/css/owl.theme.default.min.css') }}">
    <link href="{{ asset('vendor/focus-2/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/focus-2/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/focus-2/vendor/toastr/css/toastr.min.css') }}">

    <!-- Tailwind CDN for Utility CSS (optional, if needed) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-light min-h-screen text-base leading-relaxed">

    <div class="container mx-auto px-4 py-6 max-w-3xl">
        {{-- Konten utama --}}
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/focus-2/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/js/custom.min.js') }}"></script>

    <!-- Chart & Graph -->
    <script src="{{ asset('vendor/focus-2/vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/morris/morris.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/circle-progress/circle-progress.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/gaugeJS/dist/gauge.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/jqvmap/js/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/jqvmap/js/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/vendor/jquery.counterup/jquery.counterup.min.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('vendor/focus-2/vendor/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/js/plugins-init/toastr-init.js') }}"></script>

    <!-- SweetAlert -->
    <script src="{{ asset('vendor/focus-2/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/js/plugins-init/sweetalert.init.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
