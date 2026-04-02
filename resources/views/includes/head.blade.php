<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Naret Company</title>
<!-- <link rel="icon" href="assets/dist/img/logo.jpg">-->
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- iCheck -->
<link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}?v={{ filemtime(public_path('assets/dist/css/adminlte.min.css')) }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}">
<!-- Google Font: Source Sans Pro -->


<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<!-- BS Stepper -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard-modern.css') }}?v={{ filemtime(public_path('css/dashboard-modern.css')) }}">
@php($dashboardModernCssPath = public_path('css/dashboard-modern.css'))
@if (is_file($dashboardModernCssPath))
<style>
{!! file_get_contents($dashboardModernCssPath) !!}
</style>
@endif
{{-- <link rel="stylesheet" href="{{asset('assets/dist/css/skins/skin-blue.min.css')}} "> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- Include jQuery -->
{{--     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <!-- Include Bootstrap JS -->

    {{-- <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script> --}}
    <!-- Include Select2 CSS and JS -->

<style>
    /* 
    table.example1 td {
        white-space: normal !important;
        word-wrap: break-word;
    }
    .text-wrap {
    white-space: normal !important;
    word-break: break-word;
}*/

  /* Target only the Description column (2nd column in your table) */
    #example3 td:nth-child(2),
    #example3 th:nth-child(2) {
        white-space: normal !important;
        word-break: break-word;   /* ensures long words also break */
        max-width: 300px;         /* optional: control width */
    }

</style>
