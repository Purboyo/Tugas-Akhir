<!DOCTYPE html>
<html lang="en" class="form-screen">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Admin One Tailwind CSS Admin Dashboard</title>

  <!-- Tailwind is included -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-one/dist/css/main.css') }}">
  <script src="{{ asset('vendor/admin-one/dist/js/main.js') }}"></script>

  <!-- Font Awesome  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div id="app">

  <section class="section main-section">
    <div class="card">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="fa fa-lock"></i></span>
          Login
        </p>
      </header>
      <div class="card-content">
        <form method="post" action="{{ url('login') }}">
          @csrf
          <div class="field spaced">
            <label class="label">Login</label>
            <div class="control icons-left">
              <input class="input" type="email" name="email" placeholder="user@example.com" autocomplete="username" required>
              <span class="icon is-small left"><i class="fa fa-user"></i></span>
            </div>
            <p class="help">
              Please enter your login
            </p>
          </div>

          <div class="field spaced">
            <label class="label">Password</label>
            <p class="control icons-left">
              <input class="input" type="password" name="password" placeholder="Password" autocomplete="current-password" required>
              <span class="icon is-small left"><i class="fa fa-key"></i></span>
            </p>
            <p class="help">
              Please enter your password
            </p>
          </div>

          <hr>

          <div class="field grouped">
            <div class="control">
              <button type="submit" class="button blue">
                Login
              </button>
            </div>
            <div class="control">
              <a href="index.html" class="button">
                Back
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

</div>

<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=658339141622648&ev=PageView&noscript=1"/></noscript>

</body>
</html>
