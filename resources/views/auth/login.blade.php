<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Login</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
    <meta name="keywords"
        content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
    <meta name="author" content="CodedThemes">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('template/dist') }}/assets/images/macan.jpg" type="image/x-icon">
    <!-- [Google Font] Family -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/fonts/tabler-icons.min.css">
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/fonts/feather.css">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/fonts/fontawesome.css">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/fonts/material.css">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/css/style-preset.css">

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

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

                    <a href="#">
                        <img src="{{ asset('template/dist/assets/images/herbalife.png') }}" alt="img"
                            style="width: 300px; height: auto;"> <!-- Sesuaikan ukuran width -->
                    </a>
                    {{-- <a href="#"><img src="{{ asset('template/dist/assets/images/logo-dark.svg') }}"
                            alt="img"></a> --}}
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Login</b></h3>
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Lupa Password?') }}
                            </a>
                        </div>
                        <form action="{{ route('login') }}" method="post">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                    autocomplete="off">
                                @error('email')
                                    <small class="text-danger">{{ 'Email Salah' }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="password">
                                @error('password')
                                    <small class="text-danger">{{ 'Password Salah' }}</small>
                                @enderror
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>

                        {{-- TAMBAHKAN BAGIAN INI --}}
                        <div class="text-center my-3">
                            <p class="text-muted">atau</p>
                            <a href="{{ route('public.stok.index') }}">Lihat Ketersediaan Stok Produk</a>
                        </div>
                        {{-- <div class="saprator mt-3">
                            <span>Login with</span>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col-4">
                                <div class="d-grid">
                                    <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                                        <img src="{{ asset('template/dist') }}/assets/images/authentication/google.svg"
                                            alt="img"> <span class="d-none d-sm-inline-block"> Google</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                                        <img src="{{ asset('template/dist') }}/assets/images/authentication/twitter.svg"
                                            alt="img"> <span class="d-none d-sm-inline-block"> Twitter</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                                        <img src="{{ asset('template/dist/assets/images/authentication/facebook.svg') }}"
                                            alt="Facebook">
                                        <span class="d-none d-sm-inline-block"> Facebook</span>
                                    </button>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="auth-footer row">
                    <!-- <div class=""> -->
                    <div class="col my-1">
                        <p class="m-0">Copyright © <a href="#">Husen Arif Budi Setiawan</a></p>
                    </div>
                    <div class="col my-1">
                        <p class="m-0">Distributed by <a href="#">Klub Nutrisi Berkat Ceria </a></p>
                    </div>
                    <div class="col-auto my-1">
                        <ul class="list-inline footer-link mb-0">
                            <li class="list-inline-item"><a href="#">Herbalife</a></li>
                            <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                            <li class="list-inline-item"><a href="#">Contact us</a></li>
                        </ul>
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    {{-- <script src=" {{ asset('template/dist') }}/assets/js/plugins/popper.min.js"></script>
    <script src=" {{ asset('template/dist') }}/assets/js/plugins/simplebar.min.js"></script>
    <script src=" {{ asset('template/dist') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src=" {{ asset('template/dist') }}/assets/js/fonts/custom-font.js"></script>
    <script src=" {{ asset('template/dist') }}/assets/js/pcoded.js"></script>
    <script src=" {{ asset('template/dist') }}/assets/js/plugins/feather.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384..."
        crossorigin="anonymous"></script>





    <script>
        layout_change('light');
    </script>




    <script>
        change_box_container('false');
    </script>



    <script>
        layout_rtl_change('false');
    </script>


    <script>
        preset_change("preset-1");
    </script>


    <script>
        font_change("Public-Sans");
    </script>



</body>
<!-- [Body] end -->

</html>
