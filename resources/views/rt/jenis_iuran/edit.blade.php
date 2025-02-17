@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Jenis Iuran</h2>

        <form action="{{ route('manage-rt.jenis-iuran.update', $jenisIuran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Jenis Iuran</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $jenisIuran->name }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
