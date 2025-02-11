@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Buat Permission Baru</h1>

        {{-- Menampilkan pesan sukses jika ada --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form untuk membuat permission baru --}}
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf

            {{-- Nama Permission --}}
            <div class="form-group">
                <label for="name">Nama Permission</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>

                {{-- Menampilkan pesan error jika ada --}}
                @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <button type="submit" class="btn btn-primary mt-3">Simpan Permission</button>
        </form>
    </div>
@endsection
