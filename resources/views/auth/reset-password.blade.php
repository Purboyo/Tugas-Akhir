<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reset Password - LabSI</title>
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
                                    <h4 class="text-center mb-4">Reset Password</h4>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (!session('success'))
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <input type="hidden" name="email" value="{{ $email }}">

                                        <div class="form-group mt-3">
                                            <label><strong>Password Baru</strong></label>
                                            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru" required>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label><strong>Konfirmasi Password</strong></label>
                                            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru" required>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                                        </div>
                                    </form>
                                    </div>
                                    @endif
                                    <div class="text-center mt-3">
                                        <a href="{{ route('login') }}" class="text-primary">Kembali ke Login</a>
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
