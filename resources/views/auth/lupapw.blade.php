<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/auth.css') }}">
    @include('include.style')
</head>

<body>
    <script src="{{ asset('template/assets/static/js/initTheme.js') }}"></script>
    <div id="auth">
        
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="index.html"><img src="{{ asset('template/assets/compiled/svg/logo.svg') }}" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Lupa Password</h1>
            <p class="auth-subtitle mb-5">Tautan setel ulang kata sandi akan dikirim ke email anda.</p>

            <form action="index.html">
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="email" class="form-control form-control-xl" placeholder="Email">
                    <div class="form-control-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Kirim</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class='text-gray-600'>Ingat akun? <a href="{{ route('login') }}" class="font-bold">Log in</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">

        </div>
    </div>
</div>
    </div>
</body>

</html>