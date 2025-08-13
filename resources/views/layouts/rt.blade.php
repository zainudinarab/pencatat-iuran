<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6.5.0 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        :root {
            --primary: #4e73df;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f8f9fa;
            --sidebar-active: #e3e7f1;
            --text-dark: #2c374c;
            --text-muted: #6c757d;
            --border-light: #e3e6f0;
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            color: #495057;
        }

        /* === SIDEBAR PUTIH BERSIH === */
        .sidebar {
            background: var(--sidebar-bg);
            min-height: 100vh;
            border-right: 1px solid var(--border-light);
            box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .sidebar .offcanvas-header {
            background: white;
            padding: 1rem;
            border-bottom: 1px solid var(--border-light);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .sidebar .navbar-brand {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.15rem;
        }

        .sidebar .nav-link {
            color: var(--text-muted);
            padding: 0.8rem 1rem;
            margin: 0.2rem 0.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
            font-weight: 500;
        }

        .sidebar .nav-link i {
            width: 30px;
            text-align: center;
            color: #4e73df;
            font-size: 0.95rem;
        }

        .sidebar .nav-link span {
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .sidebar .nav-link:hover {
            color: var(--primary);
            background-color: var(--sidebar-hover);
            transform: translateY(-2px);
        }

        .sidebar .nav-link.active {
            background-color: var(--sidebar-active);
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
            border-radius: 0 4px 4px 0;
        }

        .sidebar .nav-link i+span {
            margin-left: 10px;
        }

        /* === NAVBAR === */
        .navbar-admin {
            background: linear-gradient(135deg, var(--primary), #434190);
            box-shadow: var(--shadow-sm);
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }

        .navbar-brand strong {
            letter-spacing: 0.5px;
        }

        .dropdown-item.active,
        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: var(--primary);
        }

        /* === CARD & FORM === */
        .card {
            background-color: #fff;
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary), #4c51bf);
            color: white;
            font-weight: 600;
            padding: 0.8rem 1.25rem;
            font-size: 1.05rem;
        }

        .form-label {
            font-weight: 500;
            color: #2d3748;
        }

        .form-control,
        .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.6rem 0.9rem;
            transition: border-color 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(90, 103, 216, 0.25);
        }

        /* === BUTTONS === */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #4c51bf);
            border: none;
            padding: 0.5rem 1.75rem;
            font-weight: 500;
            border-radius: 0.5rem;
        }

        .btn-secondary {
            border-radius: 0.5rem;
        }

        /* === PAGE TITLE === */
        .page-title {
            font-weight: 600;
            color: #2d3748;
            position: relative;
            display: inline-block;
            padding-left: 1.2rem;
        }

        .page-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 1.1em;
            background: var(--primary);
            border-radius: 2px;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .navbar {
                overflow: visible;
            }

            .dropdown-menu {
                position: absolute !important;
                top: calc(100% + 0.25rem) !important;
                left: auto !important;
                right: 0;
                transform: none !important;
                width: 220px;
                z-index: 1050;
                border-radius: 0.5rem;
                box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            }

            .dropdown-toggle::after {
                display: inline-block;
                margin-left: 0.25rem;
                vertical-align: middle;
                content: "";
                border-top: 0.3em solid;
                border-right: 0.3em solid transparent;
                border-bottom: 0;
                border-left: 0.3em solid transparent;
            }
        }
    </style>

    @stack('styles')
    @stack('css')

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 p-0">
                <div class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebarMenu">
                    <div class="offcanvas-header">
                        <a class="navbar-brand" href="">
                            <i class="fas fa-store me-2"></i>Admin Panel
                        </a>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                            data-bs-target="#sidebarMenu" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body p-0">
                        <ul class="nav flex-column px-2 mt-2">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/rts') ? 'active' : '' }}"
                                    href="/manage-rt/rts">
                                    <i class="fas fa-users"></i>
                                    <span>Data RT</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/gangs') ? 'active' : '' }}"
                                    href="/manage-rt/gangs">
                                    <i class="fas fa-road"></i>
                                    <span>Data Gang</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/houses') ? 'active' : '' }}"
                                    href="/manage-rt/houses">
                                    <i class="fas fa-home"></i>
                                    <span>Data Rumah</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/jenis-iuran') ? 'active' : '' }}"
                                    href="/manage-rt/jenis-iuran">
                                    <i class="fas fa-list"></i>
                                    <span>Data Jenis Iuran</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/iuran-wajib') ? 'active' : '' }}"
                                    href="/manage-rt/iuran-wajib">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Data Iuran Wajib</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/pembayaran') ? 'active' : '' }}"
                                    href="/manage-rt/pembayaran">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Data Pembayaran</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/pembayaran-global') ? 'active' : '' }}"
                                    href="/manage-rt/pembayaran-global">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Pembayaran Global</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/setoran-petugas') ? 'active' : '' }}"
                                    href="/manage-rt/setoran-petugas">
                                    <i class="fas fa-hand-holding-usd"></i>
                                    <span>Data Setoran</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/bendahara/konfirmasi-setoran') ? 'active' : '' }}"
                                    href="/manage-rt/bendahara/konfirmasi-setoran">
                                    <i class="fas fa-user-check"></i>
                                    <span>Data Setoran Petugas</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/transfer-pos') ? 'active' : '' }}"
                                    href="/manage-rt/transfer-pos">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <span>Trabfer Dana</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manage-rt/pengeluaran-rt') ? 'active' : '' }}"
                                    href="/manage-rt/pengeluaran-rt">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <span>Data Pengeluaran RT</span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-lg-10 px-md-4 py-4">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-dark navbar-admin shadow rounded mb-4">
                    <div class="container-fluid">
                        <!-- Hamburger (Mobile Only) -->
                        <button class="btn btn-link text-white d-lg-none me-3" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Toggle navigation">
                            <i class="fas fa-bars fa-lg"></i>
                        </button>

                        <!-- Brand -->
                        <a class="navbar-brand d-flex align-items-center" href="">
                            <i class="fas fa-store me-2"></i>
                            <strong>Admin Panel</strong>
                        </a>

                        <!-- User Menu (Right) -->
                        <div class="d-flex ms-auto">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user-circle me-1 fa-lg"></i>
                                        <span class="d-none d-lg-inline fw-medium">Admin</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-user fa-sm me-2 text-primary"></i> Profil Saya
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-cogs fa-sm me-2 text-purple"></i> Pengaturan
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fas fa-sign-out-alt fa-sm me-2"></i> Keluar
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>
                                        </li>

                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Page Header -->
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-3 mb-4 border-bottom">
                    <h1 class="h4 page-title">
                        <i class="fas fa-edit me-2"></i>@yield('page-title')
                    </h1>
                    <a href="@yield('back-url')" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <!-- Main Content Slot -->
                <div class="row">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @stack('js')

</body>

</html>
