@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Resident</h2>
        <form action="{{ route('residents.update', $resident->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ $resident->name }}" required>
            </div>
            <div class="mb-3">
                <label for="blok" class="form-label">Blok</label>
                <input type="text" name="blok" class="form-control" value="{{ $resident->blok }}" required>
            </div>
            <div class="mb-3">
                <label for="nomor_rumah" class="form-label">Nomor Rumah</label>
                <input type="text" name="nomor_rumah" class="form-control" value="{{ $resident->nomor_rumah }}" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea name="address" class="form-control" required>{{ $resident->address }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('residents.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
