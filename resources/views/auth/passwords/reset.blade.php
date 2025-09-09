<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('herbalife.png' ) }}" type="image/png">

    <!-- [Fonts] -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/fonts/tabler-icons.min.css' ) }}">
    
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style-preset.css') }}">

    <style>
        /* Tambahan kecil untuk ikon di dalam input */
        .form-group .form-control-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }
        .form-group .form-control {
            padding-right: 40px;
        }
    </style>
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
                            <h3 class="mb-1"><b>Atur Ulang Password</b></h3>
                            <p class="text-muted text-center">Buat password baru yang kuat dan mudah Anda ingat.</p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Email (Read-only untuk keamanan) -->
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required readonly>
                                @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Password Baru -->
                            <div class="form-group mb-3 position-relative">
                                <label class="form-label">Password Baru</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Masukkan password baru" required autocomplete="new-password">
                                <i class="ti ti-eye form-control-icon" onclick="togglePassword('password')"></i>
                                @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div class="form-group mb-3 position-relative">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Ketik ulang password baru" required autocomplete="new-password">
                                <i class="ti ti-eye form-control-icon" onclick="togglePassword('password-confirm')"></i>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
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
    
    <script>
        // Fungsi untuk toggle lihat/sembunyikan password
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = "password";
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        }
    </script>
</body>
</html>
