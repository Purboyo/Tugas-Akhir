<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>LabSi </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('vendor/focus-2/images/favicon.pn')}}g">
    <link rel="stylesheet" href="{{asset('vendor/focus-2/vendor/owl-carousel/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/focus-2/vendor/owl-carousel/css/owl.theme.default.min.css')}}">
    <link href="{{asset('vendor/focus-2/vendor/jqvmap/css/jqvmap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/focus-2/css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/focus-2/vendor/toastr/css/toastr.min.css')}}">
    <link  rel="stylesheet" href="{{asset('vendor/focus-2/vendor/fullcalendar/css/fullcalendar.min.css')}}">

    <link rel="stylesheet" href="{{asset('vendor/focus-2/vendor/pickadate/themes/default.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/focus-2/vendor/pickadate/themes/default.date.css')}}">

     <link rel="stylesheet" href="{{asset ('vendor/focus-2//vendor/select2/css/select2.min.css')}}">

    @yield('styles')

</head>

<body>
@php
  $role = auth()->user()->role; // 'admin' atau 'teknisi'
@endphp
<div id="app">


    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{route ($role. '.teknisi.dashboard')}}" class="brand-logo text-white text-lg font-bold" style="text-decoration: none;">
                LabSI
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header bg-white border-bottom shadow-sm">
            <div class="header-content px-4 py-2">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between align-items-center">

                        <!-- Left Section Kosong -->
                        <div class="header-left"></div>

                        <!-- Right Section: User Dropdown -->
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link dropdown-toggle d-flex align-items-center text-dark" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-account-circle h4 mb-0 mr-1"></i>
                                    <span class="d-none d-md-inline">{{ ucfirst(Auth::user()->name) }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right shadow">
                                    <h6 class="dropdown-header">Hi, {{ ucfirst(Auth::user()->name) }}</h6>
                                    <div class="dropdown-divider"></div>

                                    <!-- Profile Modal Trigger -->
                                    <a href="#" class="dropdown-item d-flex align-items-center" data-toggle="modal" data-target="#profileModal">
                                        <i class="mdi mdi-account-box-outline mr-2"></i>
                                        <span>Profile</span>
                                    </a>

                                    <!-- Logout -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                            <i class="mdi mdi-logout mr-2"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>


        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Menu</li>
                    <li><a class="" href="{{route ($role. '.teknisi.dashboard')}}" aria-expanded="false"><i
                                class="mdi mdi-view-dashboard"></i><span class="nav-text">Dashboard</span></a>
                    </li>
                    <li><a class="" href="{{route ($role. '.lab.index')}}" aria-expanded="false"><i
                                class="mdi mdi-google-classroom"></i><span class="nav-text">Laboratory</span></a>
                    </li>
                    <li><a class="" href="{{route ($role. '.form.index')}}" aria-expanded="false"><i
                                class="mdi mdi-form-select"></i><span class="nav-text">Forms</span></a>
                    </li>
                    <li><a class="" href="{{route ($role. '.pc.index')}}" aria-expanded="false"><i
                                class="mdi mdi-laptop"></i><span class="nav-text">PC</span></a>
                    </li>
                    <li><a class="" href="{{route ($role. '.report.index')}}" aria-expanded="false"><i
                                class="mdi mdi-file-document-outline"></i><span class="nav-text">Reports</span></a>
                    </li>
                    <li><a class="" href="{{route ($role. '.labReports')}}" aria-expanded="false"><i
                                class="mdi mdi-file-document-outline"></i><span class="nav-text">laboratory Damage Report</span></a>
                    </li>
                    <li><a href="{{ route($role .'.report.history') }}"><i 
                                class="mdi mdi-history"></i><span class="nav-text">History Report</span></a>
                    </li>
                    <li><a class="" href="{{ route($role. '.maintenance.index') }}" aria-expanded="false"><i
                                class="mdi mdi-wrench"></i><span class="nav-text">Maintenance</span></a>
                    </li>
                    <li><a href="{{ route($role .'.maintenance.history') }}"><i 
                                class="mdi mdi-history"></i><span class="nav-text">History Maintenance</span></a>
                    </li>

                </ul>
            </div>


        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">


            @yield('content')

            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Purboyo Broto Umbaran</a> 2025</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->
    <!-- Modal Profile -->
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Edit Profile</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="{{ route($role . '.profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-dark">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" type="text" name="name" class="form-control" value="{{ $authUser ->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" class="form-control" value="{{ $authUser ->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password (optional)</label>
                            <input id="password" type="password" name="password" class="form-control" placeholder="Leave blank if you don't want to change">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Required vendors -->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{asset('vendor/focus-2/vendor/global/global.min.js')}}"></script>
    <script src="{{asset('vendor/focus-2/js/quixnav-init.js')}}"></script>
    <script src="{{asset('vendor/focus-2/js/custom.min.js')}}"></script>


    <!-- Vectormap -->
    <script src="{{asset('vendor/focus-2/vendor/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('vendor/focus-2/vendor/morris/morris.min.js')}}"></script>


    <script src="{{asset('vendor/focus-2/vendor/circle-progress/circle-progress.min.js')}}"></script>
    <script src="{{asset('vendor/focus-2/vendor/chart.js/Chart.bundle.min.js')}}"></script>

    <script src="{{asset('vendor/focus-2/vendor/gaugeJS/dist/gauge.min.js')}}"></script>

    <!--  flot-chart js -->
    <script src="{{asset('vendor/focus-2/vendor/flot/jquery.flot.js')}}"></script>
    <script src="{{asset('vendor/focus-2/vendor/flot/jquery.flot.resize.js')}}"></script>

    <!-- Owl Carousel -->
    <script src="{{asset('vendor/focus-2/vendor/owl-carousel/js/owl.carousel.min.js')}}"></script>

    <!-- Counter Up -->
    <script src="{{asset('vendor/focus-2/vendor/jqvmap/js/jquery.vmap.min.js')}}"></script>
    <script src="{{asset('vendor/focus-2/vendor/jqvmap/js/jquery.vmap.usa.js')}}"></script>
    <script src="{{asset('vendor/focus-2/vendor/jquery.counterup/jquery.counterup.min.js')}}"></script>

    <!-- Toastr -->
    <script src="{{asset('vendor/focus-2/vendor/toastr/js/toastr.min.js')}}"></script>

    <!-- All init script -->
    <script src="{{asset('vendor/focus-2/js/plugins-init/toastr-init.js')}}"></script>

    <script src="{{asset('vendor/focus-2/js/dashboard/dashboard-1.js')}}"></script>

    <script src="{{asset('vendor/focus-2/vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script src="{{asset('vendor/focus-2/js/plugins-init/sweetalert.init.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{asset('vendor/focus-2/vendor/fullcalendar/js/fullcalendar.min.js')}}"></script>
    <script src="{{asset('vendor/focus-2/js/plugins-init/fullcalendar-init.js')}}"></script>

    <!-- Pickdate -->{{asset ('vendor/focus-2')}}
    <script src="{{asset ('vendor/focus-2/vendor/pickadate/picker.js')}}"></script>
    <script src="{{asset ('vendor/focus-2/vendor/pickadate/picker.time.js')}}"></script>
    <script src="{{asset ('vendor/focus-2/vendor/pickadate/picker.date.js')}}"></script>
    <script src="{{asset('vendor/focus-2/js/plugins-init/pickadate-init.js')}}"></script>
    
    {{-- Select2 --}}
    <script src="{{asset ('vendor/focus-2/vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset ('vendor/focus-2/js/plugins-init/select2-init.js')}}"></script>
    @stack('scripts')
@yield('scripts')


</body>

</html>