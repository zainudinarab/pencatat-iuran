@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Resident</h1>

        <!-- Menampilkan Pesan Sukses -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @can('residents-create')
        <a href="{{ route('residents.create') }}" class="btn btn-primary mb-3">Tambah Resident</a>
        @endcan
        <!-- Tabel untuk Menampilkan Data Resident -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Nomor Telepon</th>
                    <th>RT</th>
                    <th>RW</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($residents as $index => $resident)
                    <tr>
                        <td>{{ $residents->firstItem() + $index }}</td>
                        <td>{{ $resident->id }}</td>
                        <td>{{ $resident->name }}</td>
                        <td>{{ $resident->phone_number }}</td>

                        <td>{{ $resident->RT }}</td>
                        <td>{{ $resident->RW }}</td>
                        <td>{{ $resident->address }}</td>
                        <td>
                            @can('residents-edit')
                            <a href="{{ route('residents.edit', $resident->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                            @can('residents-delete')
                            <form action="{{ route('residents.destroy', $resident->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paging untuk Tabel -->

        <div class="d-flex justify-content-center mt-4">
            {{ $residents->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
