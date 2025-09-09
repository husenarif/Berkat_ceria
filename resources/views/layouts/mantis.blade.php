<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Sistem Manajemen Stok Barang</title>

    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
    <meta name="keywords"
        content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
    <meta name="author" content="CodedThemes">

    {{-- ================================================================== --}}
    {{-- >> DEKLARASI FAVICON YANG BENAR DAN BERSIH << --}}
    {{-- Pastikan file 'herbalife.png' ada di dalam folder 'public/' --}}
    {{-- ================================================================== --}}
    <link rel="icon" href="{{ asset('template/dist/assets/images/macan.jpg') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('template/dist/assets/images/macan.jpg') }}">

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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- CSS SELECT2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    @stack('styles')
</head>

<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    {{-- ... Sisa file Anda (body, nav, header, footer, scripts) tidak perlu diubah ... --}}
    {{-- (Saya singkat di sini agar tidak terlalu panjang, cukup ganti bagian <head> saja) --}}

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('home') }}" class="b-brand text-primary">
                    <span>Sistem Manajemen Stok Barang</span>
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="{{ route('home') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Data Master</label>
                        <i class="ti ti-database"></i>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('produk.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-plant-2"></i></span>
                            <span class="pc-mtext">Produk</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('kategori.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-plant-2"></i></span>
                            <span class="pc-mtext">Kategori</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('supplier.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-plant-2"></i></span>
                            <span class="pc-mtext">Supplier</span>
                        </a>
                    </li>


                    <li class="pc-item">
                        <a href="{{ route('satuan_produk.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-plant-2"></i></span>
                            <span class="pc-mtext">Satuan</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Operasional</label>
                        <i class="ti ti-settings"></i>
                    </li>


                    <li class="pc-item">
                        <a href="{{ route('stok_masuk.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-color-swatch"></i></span>
                            <span class="pc-mtext">Stok Masuk</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('transaksi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-color-swatch"></i></span>
                            <span class="pc-mtext">Transaksi</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('laporan.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-file-text"></i></span>
                            <span class="pc-mtext">Laporan</span>
                        </a>
                    </li>

                      @auth
                        @if (Auth::user()->role_id == 1)
                            <li class="pc-item">
                                <a href="{{ route('history.index') }}" class="pc-link">
                                    <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                                    <span class="pc-mtext">Riwayat</span>
                                </a>
                            </li>
                        @endif
                    @endauth
                    @auth
                        @if (Auth::user()->role_id == 1)
                            <li class="pc-item">
                                <a href="{{ route('user.index') }}" class="pc-link">
                                    <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                                    <span class="pc-mtext">Otoritas</span>
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <!-- ======= Menu collapse Icon ===== -->
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item d-inline-flex d-md-none">
                        <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ti ti-search"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <i data-feather="search"></i>
                                    <input type="search" class="form-control border-0 shadow-none"
                                        placeholder="Search here. . .">
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="pc-h-item d-none d-md-inline-flex">
                        <form class="header-search">
                            <i data-feather="search" class="icon-search"></i>
                            <input type="search" class="form-control" placeholder="Search here. . .">
                        </form>
                    </li>
                </ul>
            </div>
            <!-- [Mobile Media Block end] -->
            <div class="ms-auto">
                <ul class="list-unstyled">
                    {{-- <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ti ti-mail"></i>
                        </a> --}}
                    {{-- <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <h5 class="m-0">Message</h5>
                                <a href="#!" class="pc-head-link bg-transparent"><i
                                        class="ti ti-x text-danger"></i></a>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                                style="max-height: calc(100vh - 215px)">
                                <div class="list-group list-group-flush w-100">
                                    <a class="list-group-item list-group-item-action">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('template/dist') }}/assets/images/user/avatar-2.jpg"
                                                    alt="user-image" class="user-avtar">
                                            </div>
                                            <div class="flex-grow-1 ms-1">
                                                <span class="float-end text-muted">3:00 AM</span>
                                                <p class="text-body mb-1">It's <b>Cristina danny's</b> birthday today.
                                                </p>
                                                <span class="text-muted">2 min ago</span>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="list-group-item list-group-item-action">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('template/dist') }}/assets/images/user/avatar-1.jpg"
                                                    alt="user-image" class="user-avtar">
                                            </div>
                                            <div class="flex-grow-1 ms-1">
                                                <span class="float-end text-muted">6:00 PM</span>
                                                <p class="text-body mb-1"><b>Aida Burg</b> commented your post.</p>
                                                <span class="text-muted">5 August</span>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="list-group-item list-group-item-action">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('template/dist') }}/assets/images/user/avatar-3.jpg"
                                                    alt="user-image" class="user-avtar">
                                            </div>
                                            <div class="flex-grow-1 ms-1">
                                                <span class="float-end text-muted">2:45 PM</span>
                                                <p class="text-body mb-1"><b>There was a failure to your setup.</b></p>
                                                <span class="text-muted">7 hours ago</span>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="list-group-item list-group-item-action">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('template/dist') }}/assets/images/user/avatar-4.jpg"
                                                    alt="user-image" class="user-avtar">
                                            </div>
                                            <div class="flex-grow-1 ms-1">
                                                <span class="float-end text-muted">9:10 PM</span>
                                                <p class="text-body mb-1"><b>Cristina Danny </b> invited to join <b>
                                                        Meeting.</b></p>
                                                <span class="text-muted">Daily scrum meeting time</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="text-center py-2">
                                <a href="#!" class="link-primary">View all</a>
                            </div>
                        </div> --}}
                    {{-- </li> --}}
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside"
                            aria-expanded="false">
                            <img src="{{ asset('template/dist') }}/assets/images/user/avatar-2.jpg" alt="user-image"
                                class="user-avtar">
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('template/dist') }}/assets/images/user/avatar-2.jpg"
                                            alt="user-image" class="user-avtar wid-35">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                                        <span>{{ auth()->user()->role->role_name }}</span>
                                    </div>

                                    <a href="#" class="pc-head-link bg-transparent" id="logout-button">
                                        <i class="ti ti-power text-danger"></i>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>

                            <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
                                {{-- <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="drp-t1" data-bs-toggle="tab"
                                        data-bs-target="#drp-tab-1" type="button" role="tab"
                                        aria-controls="drp-tab-1" aria-selected="true"><i class="ti ti-user"></i>
                                        Profile</button>
                                </li> --}}
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="drp-t2" data-bs-toggle="tab"
                                        data-bs-target="#drp-tab-2" type="button" role="tab"
                                        aria-controls="drp-tab-2" aria-selected="false"><i
                                            class="ti ti-settings"></i> Setting</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="mysrpTabContent">
                                <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                    aria-labelledby="drp-t1" tabindex="0">
                                </div>
                                <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2"
                                    tabindex="0">

                                    <a href="{{ route('password.edit.local') }}" class="dropdown-item">
                                        <i class="ti ti-edit-circle"></i>
                                        <span>Edit Password</span>
                                    </a>

                                    <a href="#" class="dropdown-item" id="logout-button-dropdown">
                                        <i class="ti ti-power text-danger"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Klub Nutrisi Berkat Ceria Purwokerto</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->

               <div class="row">
                {{-- ========================================================== --}}
                {{-- >> TAMBAHKAN BLOK INI UNTUK MENAMPILKAN SEMUA ERROR << --}}
                {{-- ========================================================== --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">Terjadi Kesalahan Validasi!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="">
                        <div class="alert alert-success" id="success-alert" role="alert">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                @yield('content')
            </div>
    <!-- [ Main Content ] end -->
    {{-- <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm my-1">
                    <p class="m-0">Mantis &#9829; crafted by Team <a
                            href="https://themeforest.net/user/codedthemes" target="_blank">Codedthemes</a></p>
                </div>
                <div class="col-sm my-1">
                    <p class="m-0">Distributed by <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
                    </p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item"><a href="../index.html">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("#success-alert").fadeTo(2500, 500).slideUp(500, function() {
                $("#success-alert").slideUp(500);
            });
        });
    </script>
    <!-- [Page Specific JS] start -->
    <script src="{{ asset('template/dist') }}/assets/js/plugins/apexcharts.min.js"></script>
    <!-- [Page Specific JS] end -->
    <!-- Required Js -->
    <script src="{{ asset('template/dist') }}/assets/js/plugins/popper.min.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/fonts/custom-font.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/pcoded.js"></script>
    <script src="{{ asset('template/dist') }}/assets/js/plugins/feather.min.js"></script>


    <!-- Feather Icons dan Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384..."
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>


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

    @include('sweetalert::alert')


    {{-- JS SELECT2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    @stack('scripts')


    <!-- Logout Script -->
    <script>
        $(document).ready(function() {
            const handleLogout = function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Anda akan keluar dari sesi ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Keluar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#logout-form').submit();
                    }
                });
            };

            $('#logout-button').on('click', handleLogout);
            $('#logout-button-dropdown').on('click', handleLogout);
        });
    </script>


</body>
<!-- [Body] end -->

</html>
