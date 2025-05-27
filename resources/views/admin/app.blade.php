<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Admin One Tailwind CSS Admin Dashboard</title>

  <!-- Tailwind CSS -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-one/dist/css/main.css') }}">
  <script defer src="{{ asset('vendor/admin-one/dist/js/main.js') }}"></script>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{asset('vendor//dist/css/bootstrap.min.css')}}">

  <!-- Font Awesome & Material Design Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">

  <!-- Sortable JS -->
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>
<body>
@php
  $role = auth()->user()->role; // 'admin' atau 'teknisi'
@endphp

<div id="app">

<!-- NAVBAR -->
<nav id="navbar-main" class="navbar is-fixed-top">
  <div class="navbar-brand">
    <a class="navbar-item mobile-aside-button">
      <span class="icon"><i class="mdi mdi-menu mdi-24px"></i></span>
    </a>
  </div>

  <div class="navbar-brand is-right">
    <a id="navbarToggle" class="navbar-item --jb-navbar-menu-toggle" data-target="navbar-menu">
      <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
    </a>
  </div>

  <div class="navbar-menu" id="navbar-menu">
    <div class="navbar-end">
      <div class="navbar-item dropdown has-divider has-user-avatar">
        <a class="navbar-link" href="javascript:void(0);">
          <div class="is-user-name"><span>{{ Auth::user()->name }}</span></div>
          <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
        </a>
        <div class="navbar-dropdown">
          <a href="profile.html" class="navbar-item">
            <span class="icon"><i class="mdi mdi-account"></i></span>
            <span>My Profile</span>
          </a>
          <a class="navbar-item">
            <span class="icon"><i class="mdi mdi-bell"></i></span>
            <span>Notifications</span>
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="icon"><i class="mdi mdi-logout"></i></span>
            <span>Log Out</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- SIDEBAR -->
<aside class="aside is-placed-left is-expanded">
  <div class="aside-tools">
    <div>Admin <b class="font-black">One</b></div>
  </div>
  <div class="menu is-menu-main">
    <p class="menu-label">General</p>
    <ul class="menu-list">
      <li class="{{ Request::routeIs($role . '.admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route($role . '.admin.dashboard') }}">
          <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
          <span class="menu-item-label">Dashboard</span>
        </a>
      </li>
    </ul>
    <p class="menu-label">Examples</p>
    <ul class="menu-list">
      <li class="{{ Request::routeIs($role . '.user.index') ? 'active' : '' }}">
        <a href="{{ route($role . '.user.index') }}">
          <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
          <span class="menu-item-label">User</span>
        </a>
      </li>
      <li class="{{ Request::routeIs($role . '.lab.index') ? 'active' : '' }}">
        <a href="{{ route($role . '.lab.index') }}">
          <span class="icon"><i class="mdi mdi-google-classroom"></i></span>
          <span class="menu-item-label">Laboratory</span>
        </a>
      </li>
      <li class="{{ Request::routeIs($role . '.form.index') ? 'active' : '' }}">
        <a href="{{ route($role . '.form.index') }}">
          <span class="icon"><i class="mdi mdi-form-select"></i></span>
          <span class="menu-item-label">Forms</span>
        </a>
      </li>
      <li class="{{ Request::routeIs($role . '.pc.index') ? 'active' : '' }}">
        <a href="{{ route($role . '.pc.index') }}">
          <span class="icon"><i class="mdi mdi-laptop"></i></span>
          <span class="menu-item-label">PC</span>
        </a>
      </li>
      <li class="{{ Request::routeIs($role . '.report.index') ? 'active' : '' }}">
        <a href="{{ route($role . '.report.index') }}">
          <span class="icon"><i class="mdi mdi-file-document-outline"></i></span>
          <span class="menu-item-label">Reports</span>
        </a>
      </li>
      <li class="{{ Request::routeIs($role . '.history.index') ? 'active' : '' }}">
        <a href="">
          <span class="icon"><i class="mdi mdi-history"></i></span>
          <span class="menu-item-label">History</span>
        </a>
      </li>
      <li>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <span class="icon"><i class="mdi mdi-logout"></i></span>
          <span class="menu-item-label">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </div>
</aside>

<!-- KONTEN UTAMA -->
@yield('content')

<!-- FOOTER -->
<footer class="footer">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
    <div class="flex items-center justify-start space-x-3">
      <div>© 2025, Purboyo Broto Umbaran</div>
    </div>
  </div>
</footer>

</div>
</body>
</html>
