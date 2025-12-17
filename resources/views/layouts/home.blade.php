<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }}</title>
    <!-- My CSS -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/assets/css/style.css" />
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('ekapta') }}/bootstrap/dist/css/bootstrap.rtl.min.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="https://unsiq.ac.id/img/UNSIQ-bunder.ico" type="image/x-icon"> 
</head>
<body>

    <!-- Content -->

    @yield('content')

    <!-- End Content -->

    <!-- Bootstrap JS -->
    <script src="{{ asset('ekapta') }}/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('ekapta') }}/assets/js/jquery-1.10.2.js"></script>
    <script src="{{ asset('ekapta') }}/assets/js/main.js"></script>
    <!-- Swetalert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('error'))
    <script>
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
    @endif

</body>
</html>
