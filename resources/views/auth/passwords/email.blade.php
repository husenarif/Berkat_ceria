{{-- Hapus @extends('layouts.app') karena kita akan membuat halaman mandiri yang mirip halaman login --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lupa Password</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('herbalife.png' ) }}" type="image/png">

    <!-- [Fonts] -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style.css' ) }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style-preset.css') }}">
</head>

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header">
                    {{-- Ganti dengan logo Anda --}}
                    <a href="{{ route('login') }}">
                        <img src="{{ asset('template/dist/assets/images/herbalife.png') }}" alt="Logo" style="width: 300px; height: auto;">
                    </a>
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <h3 class="mb-1"><b>Lupa Password?</b></h3>
                            <p class="text-muted text-center">Jangan khawatir! Masukkan email Anda di bawah ini dan kami akan mengirimkan link untuk mengatur ulang password Anda.</p>
                        </div>

                        {{-- Menampilkan status session jika ada (misal: setelah link dikirim) --}}
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label class="form-label" for="email">Email Address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan email terdaftar Anda">
                                @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="auth-footer">
                    <p class="text-center my-1">
                        Ingat password Anda? <a href="{{ route('login') }}" class="link-primary">Kembali ke Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="{{ asset('template/dist/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('template/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
</body>
</html>
