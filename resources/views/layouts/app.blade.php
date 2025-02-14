<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Griya Asumta
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            
                            {{-- Menu untuk role warga --}}
                            

                            @can('residents-view')
                            <li class="nav-item">
                                <a class="nav-link" href="/residents">Data Warga</a>
                            </li>
                            @endcan

                            @can('penarikans-view')
                            <li class="nav-item">
                                <a class="nav-link" href="/penarikan">Penarikan</a>
                            </li>
                            @endcan

                            @can('setorans-view')
                            <li class="nav-item">
                                <a class="nav-link" href="/setoran">Setoran</a>
                            </li>
                            @endcan

                            <li><a class="nav-link" href="{{ route('users.index') }}">Manage Users</a></li>

                            @can('konfirmasi_setorans-view')
                            <li class="nav-item">
                                <a class="nav-link" href="/confirm-setoran">Confirm Setoran</a>
                            </li>
                            @endcan

                            @can('pengeluaran-view')
                            <li class="nav-item">
                                <a class="nav-link" href="/pengeluaran">Pengeluaran</a>
                            </li>
                            @endcan

                            @can('saldo-view')
                            <li class="nav-item">
                                <a class="nav-link" href="/saldo">Saldo</a>
                            </li>
                            @endcan

                            @can('laporan-view')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarlaporanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Laporan
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarlaporanDropdown">
                                    <li><a class="dropdown-item" href="/laporan">Laporan RT</a></li>
                                    <li><a class="dropdown-item" href="/laporan/tarikan-by-petugas">Laporan Petugas</a></li>
                                </ul>
                            </li>
                            @endcan

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarUsersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Manage Users
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarUsersDropdown">
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}">All Users</a></li>
                                    <li><a class="dropdown-item" href="{{ route('users.create') }}">Add New User</a></li>
                                    <li><a class="dropdown-item" href="{{ route('roles.index') }}">Manage Roles</a></li>
                                    {{-- <li><a class="dropdown-item" href="{{ route('permissions.index') }}">Manage Permissions</a></li> --}}
                                </ul>
                            </li>
                            
                            
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    {{-- profile    --}}
                                    <a class="dropdown-item" href="/profile">
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
    </div>
</body>
</html>
