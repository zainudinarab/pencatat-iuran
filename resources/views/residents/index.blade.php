@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Daftar Residents</h2>
        <a href="{{ route('residents.create') }}" class="btn btn-primary mb-3">Tambah Resident</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Blok</th>
                    <th>Nomor Rumah</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($residents as $resident)
                    <tr>
                        <td>{{ $resident->name }}</td>
                        <td>{{ $resident->blok }}</td>
                        <td>{{ $resident->nomor_rumah }}</td>
                        <td>{{ $resident->address }}</td>
                        <td>
                            <a href="{{ route('residents.edit', $resident->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('residents.destroy', $resident->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
