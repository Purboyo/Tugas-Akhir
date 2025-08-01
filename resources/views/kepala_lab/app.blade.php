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
  $role = auth()->user()->role;
@endphp
<div id="app">

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">
        <div class="nav-header">
            <a href="{{route ($role. '.kepala_lab.dashboard')}}" class="brand-logo text-white text-lg font-bold" style="text-decoration: none;">
                LabSI
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

       <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    {{ ucfirst(Auth::user()->name) }} <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#profileModal">
                                        <i class="mdi mdi-account-box-outline"></i>
                                        <span class="ml-2">Profile</span>
                                    </a>

                                    <!-- Logout Form -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="mdi mdi-logout"></i>
                                            <span class="ml-2">Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        {{-- Side bar --}}
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Menu</li>
                    <li><a class="" href="{{route ($role. '.kepala_lab.dashboard')}}" aria-expanded="false"><i
                                class="mdi mdi-view-dashboard"></i><span class="nav-text">Dashboard</span></a>
                    </li>
                    </li>
                    <li><a class="" href="{{route ($role. '.labReports')}}" aria-expanded="false"><i
                                class="mdi mdi-file-document-outline"></i><span class="nav-text">laboratory Damage Report</span></a>
                    </li>
                    <li><a class="" href="{{route ($role. '.reminder.index')}}" aria-expanded="false"><i
                                class="mdi mdi-calendar-clock"></i><span class="nav-text">Reminders</span></a>
                    </li>                    
                    <li><a href="{{ route($role .'.maintenance.history') }}"><i 
                                class="mdi mdi-history"></i><span class="nav-text">History Maintenance</span></a>
                    <li><a href="{{ route($role .'.report.history') }}"><i 
                                class="mdi mdi-history"></i><span class="nav-text">History Report</span></a>
                    </li>
                </ul>
            </div>


        </div>

        {{-- Content --}}
        <div class="content-body">
            <div class="container-fluid">


            @yield('content')

            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Purboyo Broto Umbaran</a> 2025</p>
            </div>
        </div>

    </div>
 
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


</body>

</html>