@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    {{-- <div class="card-header bg-primary text-white">
                        <h3>{{ __('Dashboard') }}</h3>
                    </div> --}}

                    <div class="card-body">
                        <!-- Menampilkan pesan selamat datang dengan styling -->
                        <div class="text-center mb-4">
                            <h1 class="display-4 text-success">Selamat Datang, {{ Auth::user()->name }}!</h1>
                            <p class="lead">Anda telah berhasil login. Berikut adalah beberapa informasi penting untuk
                                Anda:</p>
                        </div>

                        <div class="row">
                            <!-- Informasi 1 -->
                            <div class="col-md-4 mb-3">
                                <div class="card text-white bg-info">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user fa-3x mb-2"></i>
                                        <h5 class="card-title">Profil Pengguna</h5>
                                        <p class="card-text">Lihat dan edit profil Anda.</p>
                                        <a href="" class="btn btn-light">Lihat Profil</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi 2 -->
                            <div class="col-md-4 mb-3">
                                <div class="card text-white bg-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-cogs fa-3x mb-2"></i>
                                        <h5 class="card-title">Pengaturan</h5>
                                        <p class="card-text">Kelola pengaturan akun Anda.</p>
                                        <a href="" class="btn btn-light">Pengaturan</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi 3 -->
                            <div class="col-md-4 mb-3">
                                <div class="card text-white bg-warning">
                                    <div class="card-body text-center">
                                        <i class="fas fa-bell fa-3x mb-2"></i>
                                        <h5 class="card-title">Notifikasi</h5>
                                        <p class="card-text">Lihat notifikasi terbaru.</p>
                                        <a href="" class="btn btn-light">Lihat Notifikasi</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Logout Button -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endpush
