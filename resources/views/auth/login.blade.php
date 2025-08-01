<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - LabSI</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="{{ asset('vendor/focus-2/css/style.css') }}" rel="stylesheet">
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form text-dark">
                                    <h4 class="text-center mb-4">Sign in to your account</h4>

                                    <!-- Pesan sukses -->
                                    @if (session('status'))
                                    <div class="alert alert-success text-center">
                                        {{ session('status') }}
                                    </div>
                                    @endif
                                    {{-- Pesan Error Umum --}}
                                    @if (session('error'))
                                        <div class="alert alert-danger text-center">
                                            {{ session('error') }}
                                        </div>
                                    @elseif ($errors->any())
                                        <div class="alert alert-danger text-center text-dark">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ url('login') }}">
                                        @csrf

                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" name="email" class="form-control" placeholder="user@example.com" required>
                                        </div>

                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <input type="password" name="password" class="form-control" placeholder="Password" value="password" required>
                                        </div>

                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-primary btn-block">Sign me in</button>
                                        </div>
                                    </form>
                                    <div class="text-center mt-3">
                                        <a href="{{ url('forgot-password') }}" class="text-primary">Forgot Password?</a>
                                    </div>                                       
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/focus-2/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('vendor/focus-2/js/custom.min.js') }}"></script>
</body>

</html>
