@extends('layouts.rt')

@section('content')
    <div class="container">
        <h2>Daftar Hubungan User dengan Rumah</h2>
        {{-- <a href="{{ route('house-user.create') }}" class="btn btn-primary mb-3">Tambah Hubungan</a> --}}

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>House</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($houseUsers as $houseUser)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $houseUser->user->name }}</td>
                        <td>{{ $houseUser->house->id }} - {{ $houseUser->house->address }}</td>
                        <td>{{ ucfirst($houseUser->role) }}</td>
                        <td>
                            <a href="{{ route('house-user.edit', $houseUser->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('house-user.destroy', $houseUser->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {!! $houseUsers->links('pagination::bootstrap-5') !!}
    </div>
@endsection
