{{-- layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>{{ $title ?? 'IT-Helpdesk' }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/AVI.png') }}">

    <!-- Fonts and Icons -->
    <link href="{{ asset('template-admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="{{ asset('template-admin/css/sb-admin-2.min.css') }}" rel="stylesheet" />

    {{-- chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
   <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    @stack('styles')

    <style>
        body {
            background-color: #ffffff !important; /* Putih bersih */
        }
    </style>
</head>


<body id="page-top" class="d-flex flex-column min-vh-100">
    <!-- Page Wrapper -->
    <div id="wrapper">
        {{-- Sidebar berdasarkan role --}}
        @if(Auth::check() && Auth::user()->role == 'admin')
            @include('layouts.sidebar-admin')
        @elseif(Auth::check() && Auth::user()->role == 'user')
            @include('layouts.sidebar-user')
        @endif

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="min-height: 100vh;">
            <!-- Main Content -->
            <div id="content" class="flex-grow-1">
                {{-- Header Dihilangkan --}}
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            
            {{-- Footer --}}
        <footer class="bg-white py-3 mt-auto">
    <div class="container my-auto">
        <div class="text-center text-muted">
            &copy; PT. Astra Visteon Indonesia {{ date('Y') }}
        </div>
    </div>
</footer>

    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>

    <!-- SB Admin 2 Scripts -->
    <script src="{{ asset('template-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template-admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template-admin/js/sb-admin-2.min.js') }}"></script>

    {{-- <!-- Chart.js -->
    <script src="{{ asset('template-admin/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('template-admin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('template-admin/js/demo/chart-pie-demo.js') }}"></script> --}}
    @stack('scripts')
</body>

</html>
