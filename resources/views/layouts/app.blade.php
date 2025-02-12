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
    @stack('css')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        {{-- @auth
                            @php
                                $role = Auth::user()->role;
                            @endphp

                            <ul class="navbar-nav">

                                @if ($role == 'warga')
                                    <li class="nav-item">
                                        <a class="nav-link" href="/profile">Profil</a>
                                    </li>
                                @endif


                                @if (in_array($role, ['petugas', 'bendahara', 'rt']))
                                    <li class="nav-item">
                                        <a class="nav-link" href="/residents">Data Warga</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/penarikan">Penarikan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/setoran">Setoran</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/confirm-setoran">Confirm Setoran</a>
                                    </li>

                                    @if ($role == 'bendahara')
                                        <li class="nav-item">
                                            <a class="nav-link" href="/pengeluaran">Pengeluaran</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/saldo">Saldo</a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a class="nav-link" href="/laporan">Laporan</a>
                                    </li>
                                @endif
                            </ul>
                        @endauth --}}

                        @auth
                            @php
                                $user = Auth::user();
                            @endphp

                            <ul class="navbar-nav">
                                {{-- Menu untuk role warga --}}
                                @if ($user->hasRole('warga'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="/profile">Profil</a>
                                    </li>
                                @endif

                                {{-- Menu untuk role petugas, bendahara, dan rt --}}
                                @if ($user->hasRole('Petugas') || $user->hasRole('Bendahara') || $user->hasRole('rt') || $user->hasRole('Admin'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="/residents">Data Warga</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/penarikan">Penarikan</a>
                                    </li>


                                    <li class="nav-item">
                                        <a class="nav-link" href="/setoran">Setoran</a>
                                    </li>

                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarlaporanDropdown"
                                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Laporan
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarlaporanDropdown">
                                            <li><a class="dropdown-item" href="/laporan">Laporan RT</a></li>
                                            <li><a class="dropdown-item" href="/laporan/tarikan-by-petugas">Laporan
                                                    Petugas</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- Menu khusus untuk bendahara --}}

                                @if ($user->hasRole('Bendahara') || $user->hasRole('Admin'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="/pengeluaran">Pengeluaran</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/saldo">Saldo</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/confirm-setoran">Confirm Setoran</a>
                                    </li>
                                @endif

                                {{-- Menu untuk admin --}}
                                @if ($user->hasRole('Admin'))
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarRoleDropdown"
                                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            management User
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarRoleDropdown">
                                            <li><a class="dropdown-item" href="/roles">Manajemen Role</a></li>
                                            <li><a class="dropdown-item" href="/permissions">Manajemen permissions</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if ($user->hasRole('Warga'))
                                @endif
                                {{-- Menu yang bisa diakses oleh semua role yang memiliki permission view_profile --}}
                                @if ($user->can('view profile'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="/profile">Profil</a>
                                    </li>
                                @endif
                            </ul>
                        @endauth

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
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
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
            @yield('content')
        </main>
    </div>
    @stack('js')
</body>

</html>
