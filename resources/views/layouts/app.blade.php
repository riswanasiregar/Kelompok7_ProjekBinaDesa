<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Projek Bina Desa') }}</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template/images/logos/favicon.png') }}">

    <!-- Template core CSS -->
    <link rel="stylesheet" href="{{ asset('template/css/icons/tabler-icons/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('template/libs/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/libs/simplebar/dist/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/libs/apexcharts/dist/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/styles.min.css') }}">
    @stack('styles')
</head>
<body>
    <div id="main-wrapper" data-simplebar>
        <!-- Sidebar -->
        <aside class="left-sidebar bg-white border-end">
            <div class="brand-logo d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center">
                    <img src="{{ asset('template/images/logos/logo-wrappixel.svg') }}" alt="Logo" height="24" class="me-2">
                    <span class="fw-semibold text-dark">Projek Bina Desa</span>
                </a>
                <a href="javascript:void(0)" class="sidebartoggler d-lg-none d-block text-muted" id="sidebarCollapse">
                    <i class="ti ti-x fs-5"></i>
                </a>
            </div>
            <nav class="sidebar-nav" data-simplebar>
                <ul id="sidebarnav" class="list-unstyled mb-0">
                    @auth
                     <li class="sidebar-item">
                        <a class="sidebar-link d-flex align-items-center px-3 py-2 {{ request()->routeIs('warga.*') ? 'active' : '' }}" href="{{ route('warga.index') }}">
                            <i class=""></i>
                            <span>Data Warga</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-item">
                        <a class="sidebar-link d-flex align-items-center px-3 py-2 {{ request()->routeIs('program_bantuan.*') ? 'active' : '' }}" href="{{ route('program_bantuan.index') }}">
                            <i class=""></i>
                            <span>Program Bantuan</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link d-flex align-items-center px-3 py-2 {{ request()->routeIs('pendaftar-bantuan.*') ? 'active' : '' }}" href="{{ route('pendaftar-bantuan.index') }}">
                            <i class=""></i>
                            <span>Pendaftaran Bantuan</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link d-flex align-items-center px-3 py-2 {{ request()->routeIs('verifikasi.*') ? 'active' : '' }}" href="{{ route('verifikasi.index') }}">
                            <i class=""></i>
                            <span>Verifikasi Lapangan</span>
                        </a>
                    </li>

                   <li class="sidebar-item">
                        <a class="sidebar-link d-flex align-items-center px-3 py-2 {{ request()->routeIs('penerima.*') ? 'active' : '' }}" href="{{ route('penerima.index') }}">
                            <i class=""></i>
                            <span>Penerima Bantuan</span>
                        </a>
                    </li>

                     <li class="sidebar-item">
                        <a class="sidebar-link d-flex align-items-center px-3 py-2 {{ request()->routeIs('riwayat.*') ? 'active' : '' }}" href="{{ route('riwayat.index') }}">
                            <i class=""></i>
                            <span>Riwayat Penyaluran</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link d-flex align-items-center px-3 py-2 {{ request()->routeIs('uploads') ? 'active' : '' }}" href="{{ route('uploads') }}">
                            <i class=""></i>
                            <span>Multi Upload</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-item">
                        <a class="sidebar-link d-flex align-items-center px-3 py-2 {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class=""></i>
                            <span>Manajemen Akun</span>
                        </a>
                    </li>
                   
                    @endauth
                    <!-- Tambahkan menu lain di sini -->
                </ul>
            </nav>
        </aside>

        <!-- Content area (header + page) -->
        <div id="content-area">
            <!-- Topbar -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center">
                            <a class="d-lg-none d-block me-3 text-muted" href="javascript:void(0)" id="sidebarToggle">
                                <i class="ti ti-menu-2 fs-4"></i>
                            </a>
                            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                                <span class="fw-semibold">Projek Bina Desa</span>
                            </a>
                        </div>
                        <div class="d-flex align-items-center ms-auto">
                            @auth
                                <div class="text-end me-3">
                                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                    <div class="badge bg-primary-subtle text-primary text-capitalize">{{ auth()->user()->role }}</div>
                                </div>
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm">Keluar</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Page content -->
            <div class="page-wrapper">
                <div class="container-fluid py-4">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Footer (opsional) -->
        <!-- <footer class="py-3 border-top bg-white">
            <div class="container-fluid">
                <span class="text-muted">© {{ date('Y') }} Projek Bina Desa</span>
            </div>
        </footer> -->
    </div>

    <!-- Core JS -->
    <script src="{{ asset('template/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('template/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('template/js/app.min.js') }}"></script>
    @stack('scripts')

    <style>
        .left-sidebar {background-color:#fff; box-shadow:0 0 1px rgba(0,0,0,.05), 0 8px 24px rgba(0,0,0,.06); transition:transform .25s ease;}
        #content-area {transition:margin-left .25s ease;}
        .sidebar-overlay {position:fixed; inset:0; background:rgba(0,0,0,.2); z-index:1049; display:none;}
        .sidebar-overlay.show {display:block;}
        .sidebar-link.active {background:#EEF5FF; border-radius:.375rem; color:#0d6efd;}
    </style>

    <script>
        (function () {
            const sidebar = document.querySelector('.left-sidebar');
            const contentArea = document.getElementById('content-area');
            const toggleButtons = [document.getElementById('sidebarToggle'), document.getElementById('sidebarCollapse')].filter(Boolean);
            const SIDEBAR_WIDTH = 260;
            let overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);

            function isMobile() {
                return window.innerWidth < 992;
            }
            function openSidebar() {
                sidebar.style.transform = 'translateX(0)';
                if (!isMobile()) contentArea.style.marginLeft = SIDEBAR_WIDTH + 'px';
                if (isMobile()) overlay.classList.add('show');
            }
            function closeSidebar() {
                sidebar.style.transform = 'translateX(-100%)';
                if (!isMobile()) contentArea.style.marginLeft = '0';
                overlay.classList.remove('show');
            }
            function initLayout() {
                if (isMobile()) {
                    sidebar.style.position = 'fixed';
                    sidebar.style.top = '0';
                    sidebar.style.bottom = '0';
                    sidebar.style.width = SIDEBAR_WIDTH + 'px';
                    sidebar.style.zIndex = '1050';
                    sidebar.style.transform = 'translateX(-100%)';
                    contentArea.style.marginLeft = '0';
                } else {
                    sidebar.style.position = 'fixed';
                    sidebar.style.top = '0';
                    sidebar.style.bottom = '0';
                    sidebar.style.width = SIDEBAR_WIDTH + 'px';
                    sidebar.style.transform = 'translateX(0)';
                    contentArea.style.marginLeft = SIDEBAR_WIDTH + 'px';
                }
            }

            initLayout();
            window.addEventListener('resize', initLayout);
            toggleButtons.forEach(btn => btn.addEventListener('click', function () {
                const isHidden = sidebar.style.transform === 'translateX(-100%)';
                if (isHidden) openSidebar(); else closeSidebar();
            }));
            overlay.addEventListener('click', closeSidebar);
        })();
    </script>
    <!-- Page level (opsional) -->
    <!-- <script src="{{ asset('template/libs/apexcharts/dist/apexcharts.min.js') }}"></script> -->
</body>
</html>