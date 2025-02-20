@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tambah Jenis Iuran</h2>

        <form action="{{ route('manage-rt.jenis-iuran.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Jenis Iuran</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
