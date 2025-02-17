@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-12">
                <a href="{{ route('manage-rt.jenis-iuran.create') }}" class="btn btn-primary">Tambah Jenis Iuran</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Jenis Iuran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jenisIurans as $index => $jenisIuran)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $jenisIuran->name }}</td>
                        <td>
                            <a href="{{ route('manage-rt.jenis-iuran.edit', $jenisIuran->id) }}"
                                class="btn btn-warning">Edit</a>
                            <form action="{{ route('manage-rt.jenis-iuran.destroy', $jenisIuran->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
