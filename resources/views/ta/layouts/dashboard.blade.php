<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/toastr/toastr.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="shortcut icon" href="https://unsiq.ac.id/img/UNSIQ-bunder.ico" type="image/x-icon">

     {{-- Tags --}}
     <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet"/>
     <style type="text/css">
         .bootstrap-tagsinput .tag {
             margin-right: 2px;
             color: white !important;
             background-color: #0d6efd;
             padding: 2px 4px 2px 4px;
             border-radius: 10px;
         }
     </style>
     {{-- Prevent white flash on page load --}}
     <style>
         body { background-color: #343a40 !important; }
         .content-wrapper { background-color: #f4f6f9; }
     </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include($sidebar)

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            @yield('content')

        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('ekapta') }}/adminLTE/dist/js/adminlte.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jszip/jszip.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- Toastr -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/toastr/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/sweetalert2/sweetalert2.min.js"></script>
    {{-- My JS --}}
    <script src="{{ asset('ekapta/assets/js/dashboard.js') }}"></script>
    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Alert success --}}
    @if (session('success'))
        <script>
            $(document).Toasts('create', {
                class: 'bg-success mt-5 mr-3',
                title: 'Success',
                autohide: true,
                delay: 3000,
                body: '{{ session('success') }}'
            })
        </script>
    @endif

    {{-- Alert warning --}}
    @if (session('warning'))
        <script>
            $(document).Toasts('create', {
                class: 'bg-warning mt-5 mr-3',
                title: 'Warning',
                autohide: true,
                delay: 3000,
                body: '{{ session('warning') }}'
            })
        </script>
    @endif

    {{-- Alert Error --}}
    @if (session('error'))
        <script>
            $(document).Toasts('create', {
                class: 'bg-danger mt-5 mr-3',
                title: 'Error',
                autohide: true,
                delay: 3000,
                body: '{{ session('error') }}'
            })
        </script>
    @endif

    @error('lampiran')
        <script>
            $(document).Toasts('create', {
                class: 'bg-danger mt-5 mr-3',
                title: 'Error',
                autohide: true,
                delay: 3000,
                body: '{{ $message }}'
            })
        </script>
    @enderror

    @error('lampiran_acc')
        <script>
            $(document).Toasts('create', {
                class: 'bg-danger mt-5 mr-3',
                title: 'Error',
                autohide: true,
                delay: 3000,
                body: '{{ $message }}'
            })
        </script>
    @enderror

    @if ($active == 'pengajuan' || $active == 'seminar' || $active == 'ujian' || $active == 'dosen')
        <script>
            $(document).ready(function() {
                $('.select-1').select2();
            })

            $(document).ready(function() {
                $('.select-2').select2();
            })

            $(document).ready(function() {
                $('.select-3').select2();
            })
        </script>
    @endif

    @include('layouts.js')
    @include('partials.sidebar-menu-state')
</body>

</html>
