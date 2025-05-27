<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Admin One Tailwind CSS Admin Dashboard</title>


  <!-- Tailwind is included -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-one/dist/css/main.css') }}">
  <script src="{{ asset('vendor/admin-one/dist/js/main.js') }} "></script>

  <!-- Font Awesome  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body>

<div id="app">

<nav id="navbar-main" class="navbar is-fixed-top">
  <div class="navbar-brand">
    <a class="navbar-item mobile-aside-button">
      <span class="icon"><i class="mdi mdi-menu mdi-24px"></i></span>
    </a>
  </div>
  
  <div class="navbar-brand is-right">
    <a class="navbar-item --jb-navbar-menu-toggle" data-target="navbar-menu">
      <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
    </a>
  </div>
  <div class="navbar-menu" id="navbar-menu">
    <div class="navbar-end">
      <div class="navbar-item dropdown has-divider">
        <div class="navbar-dropdown">
          <a href="profile.html" class="navbar-item">
            <span class="icon"><i class="mdi mdi-account"></i></span>
            <span>My Profile</span>
          </a>
          <a class="navbar-item">
            <span class="icon"><i class="mdi mdi-email"></i></span>
            <span>Messages</span>
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item">
            <span class="icon"><i class="mdi mdi-logout"></i></span>
            <span>Log Out</span>
          </a>
        </div>
      </div>
      <div class="navbar-item dropdown has-divider has-user-avatar">
        <a class="navbar-link">
          <div class="is-user-name"><span>John Doe</span></div>
          <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
        </a>
        <div class="navbar-dropdown">
          <a href="profile.html" class="navbar-item">
            <span class="icon"><i class="mdi mdi-account"></i></span>
            <span>My Profile</span>
          </a>
          <a class="navbar-item">
            <span class="icon"><i class="mdi mdi-settings"></i></span>
            <span>Settings</span>
          </a>
          <a class="navbar-item">
            <span class="icon"><i class="mdi mdi-email"></i></span>
            <span>Messages</span>
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item">
            <span class="icon"><i class="mdi mdi-logout"></i></span>
            <span>Log Out</span>
          </a>
        </div>
      </div>
      <a title="Log out" class="navbar-item desktop-icon-only">
        <span class="icon"><i class="mdi mdi-logout"></i></span>
        <span>Log out</span>
      </a>
    </div>
  </div>
</nav>

<aside class="aside is-placed-left is-expanded">
  <div class="aside-tools">
    <div>
      Admin <b class="font-black">One</b>
    </div>
  </div>
  <div class="menu is-menu-main">
    <p class="menu-label">General</p>
    <ul class="menu-list">
      <li class="active">
        <a href="index.html">
          <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
          <span class="menu-item-label">Dashboard</span>
        </a>
      </li>
    </ul>
    <p class="menu-label">Examples</p>
    <ul class="menu-list">
      <li class="--set-active-tables-html">
        <a href="tables.html">
          <span class="icon"><i class="mdi mdi-table"></i></span>
          <span class="menu-item-label">Tables</span>
        </a>
      </li>
      <li class="--set-active-forms-html">
        <a href="forms.html">
          <span class="icon"><i class="mdi mdi-square-edit-outline"></i></span>
          <span class="menu-item-label">Forms</span>
        </a>
      </li>
      <li class="--set-active-profile-html">
        <a href="profile.html">
          <span class="icon"><i class="mdi mdi-account-circle"></i></span>
          <span class="menu-item-label">Profile</span>
        </a>
      </li>
      <li>
        <a href="login.html">
          <span class="icon"><i class="mdi mdi-lock"></i></span>
          <span class="menu-item-label">Login</span>
        </a>
      </li>
      <li>
        <a class="dropdown">
          <span class="icon"><i class="mdi mdi-view-list"></i></span>
          <span class="menu-item-label">Submenus</span>
          <span class="icon"><i class="mdi mdi-plus"></i></span>
        </a>
        <ul>
          <li>
            <a href="#void">
              <span>Sub-item One</span>
            </a>
          </li>
          <li>
            <a href="#void">
              <span>Sub-item Two</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
    <p class="menu-label">About</p>
    <ul class="menu-list">
      <li>
        <a href="https://justboil.me" onclick="alert('Coming soon'); return false" target="_blank" class="has-icon">
          <span class="icon"><i class="mdi mdi-credit-card-outline"></i></span>
          <span class="menu-item-label">Premium Demo</span>
        </a>
      </li>
      <li>
        <a href="https://justboil.me/tailwind-admin-templates" class="has-icon">
          <span class="icon"><i class="mdi mdi-help-circle"></i></span>
          <span class="menu-item-label">About</span>
        </a>
      </li>
      <li>
        <a href="https://github.com/justboil/admin-one-tailwind" class="has-icon">
          <span class="icon"><i class="mdi mdi-github-circle"></i></span>
          <span class="menu-item-label">GitHub</span>
        </a>
      </li>
    </ul>
  </div>
</aside>

@yield('content')

<footer class="footer">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
    <div class="flex items-center justify-start space-x-3">
      <div>
        © 2021, JustBoil.me
      </div>

      <div>
        <p>Distributed By: <a href="https://themewagon.com/" target="_blank">ThemeWagon</a></p>
      </div>
      <a href="https://github.com/justboil/admin-one-tailwind" style="height: 20px">
        <img src="https://img.shields.io/github/v/release/justboil/admin-one-tailwind?color=%23999">
      </a>
    </div>

  </div>
</footer>

<div id="sample-modal" class="modal">
  <div class="modal-background --jb-modal-close"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Sample modal</p>
    </header>
    <section class="modal-card-body">
      <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
      <p>This is sample modal</p>
    </section>
    <footer class="modal-card-foot">
      <button class="button --jb-modal-close">Cancel</button>
      <button class="button red --jb-modal-close">Confirm</button>
    </footer>
  </div>
</div>

<div id="sample-modal-2" class="modal">
  <div class="modal-background --jb-modal-close"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Sample modal</p>
    </header>
    <section class="modal-card-body">
      <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
      <p>This is sample modal</p>
    </section>
    <footer class="modal-card-foot">
      <button class="button --jb-modal-close">Cancel</button>
      <button class="button blue --jb-modal-close">Confirm</button>
    </footer>
  </div>
</div>

</div>


{{-- toggle mobile aside --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.mobile-aside-button').forEach(el => {
      el.addEventListener('click', () => {
        document.documentElement.classList.toggle('aside-mobile-expanded');
      });
    });
  });
</script>

{{-- toggle navbar menu --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Navbar toggle
    const navbarToggle = document.querySelector('.--jb-navbar-menu-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    
    if (navbarToggle && navbarMenu) {
      navbarToggle.addEventListener('click', function(e) {
        e.preventDefault();
        navbarMenu.classList.toggle('active');
        
        // Toggle icon
        const icon = this.querySelector('.mdi');
        if (icon) {
          icon.classList.toggle('mdi-dots-vertical');
          icon.classList.toggle('mdi-close');
        }
      });
    }
    
    // Close when clicking outside
    document.addEventListener('click', function(event) {
      if (navbarMenu && navbarToggle && 
          !navbarMenu.contains(event.target) && 
          !navbarToggle.contains(event.target)) {
        navbarMenu.classList.remove('active');
        
        // Reset icon
        const icon = navbarToggle.querySelector('.mdi');
        if (icon && icon.classList.contains('mdi-close')) {
          icon.classList.remove('mdi-close');
          icon.classList.add('mdi-dots-vertical');
        }
      }
    });
  });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
  // Handle dropdown user profile
  const userDropdown = document.querySelector('.has-user-avatar .navbar-link');
  const userDropdownMenu = document.querySelector('.has-user-avatar .navbar-dropdown');
  
  if (userDropdown && userDropdownMenu) {
    userDropdown.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      userDropdownMenu.classList.toggle('active');
      
      // Toggle icon
      const icon = this.querySelector('.mdi');
      if (icon) {
        icon.classList.toggle('mdi-chevron-down');
        icon.classList.toggle('mdi-chevron-up');
      }
    });
    
    // Close when clicking outside
    document.addEventListener('click', function() {
      userDropdownMenu.classList.remove('active');
      const icon = userDropdown.querySelector('.mdi');
      if (icon && icon.classList.contains('mdi-chevron-up')) {
        icon.classList.remove('mdi-chevron-up');
        icon.classList.add('mdi-chevron-down');
      }
    });
  }
  });
  </script>

</body>
</html>
