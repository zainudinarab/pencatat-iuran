@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ubah Role Pengguna</h1>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Pengguna --}}
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" class="form-control" value="{{ $user->name }}" readonly>
            </div>

            {{-- Email Pengguna --}}
            <div class="form-group mt-3">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
            </div>

            {{-- Role Pengguna --}}
            <div class="form-group mt-3">
                <label for="roles">Pilih Role</label>
                <select name="roles[]" id="roles" class="form-control" multiple>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @if ($user->hasRole($role->name)) selected @endif>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('roles')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Update --}}
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
