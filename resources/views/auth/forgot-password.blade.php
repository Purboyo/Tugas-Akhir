<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Forgot Password - LabSI</title>
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
                                    <h4 class="text-center mb-4">Forgot Password</h4>

                                    @if (session('status'))
                                        <div class="alert alert-success text-center">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger text-center">
                                            @foreach ($errors->all() as $error)
                                                <div>{{ $error }}</div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('forgot-password.post') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" name="email" class="form-control" placeholder="user@example.com" required>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
                                        </div>
                                    </form>

                                    <div class="text-center mt-3">
                                        <a href="{{route('login')}}" class="text-primary">Back to Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
