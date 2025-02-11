<!-- resources/views/welcome.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h3>Selamat Datang di Aplikasi Kami!</h3>
                    </div>

                    <div class="card-body text-center">
                        <h1 class="display-4 text-success mb-4">Hai, Pengguna Baru!</h1>
                        <p class="lead">Aplikasi ini membantu Anda untuk melakukan [deskripsi aplikasi].</p>
                        <p>Silakan login untuk memulai.</p>

                        <div class="mt-4">
                            <!-- Tombol Login -->
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login</a>
                            <!-- Tombol Register -->
                            <a href="{{ route('register') }}" class="btn btn-success btn-lg ml-2">Daftar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
