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
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-bell"></i>
                                    <div class="pulse-css"></div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="list-unstyled">
                                        <li class="media dropdown-item">
                                            <span class="success"><i class="ti-user"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong>Martin</strong> has added a <strong>customer</strong> Successfully</p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <!-- Item notifikasi lainnya tetap -->
                                    </ul>
                                    <a class="all-notification" href="#">See all notifications <i class="ti-arrow-right"></i></a>
                                </div>
                            </li>
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    {{ ucfirst(Auth::user()->name) }} <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ asset('vendor/focus-2/app-profile.html') }}" class="dropdown-item">
                                        <i class="mdi mdi-account-box-outline"></i>
                                        <span class="ml-2">Profile</span>
                                    </a>
                                    <a href="{{ asset('vendor/focus-2/email-inbox.html') }}" class="dropdown-item">
                                        <i class="mdi mdi-email-outline"></i>
                                        <span class="ml-2">Inbox</span>
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
                    <li><a class="" href="{{route ($role. '.maintenance.index')}}" aria-expanded="false"><i
                                class="mdi mdi-wrench"></i><span class="nav-text">Maintenance</span></a>
                    </li>
                    <li><a class="" href="" aria-expanded="false"><i
                                class="mdi mdi-history"></i><span class="nav-text">History</span></a>
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


    <script src="{{asset('vendor/focus-2/js/dashboard/dashboard-1.js')}}"></script>

</body>

</html>