@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Pengguna</h1>

        {{-- Menampilkan pesan sukses jika ada --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel Daftar Pengguna --}}
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td>
                            {{-- Tombol untuk Mengubah Role Pengguna --}}
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Ubah Role</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
