@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Permission</h1>
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">Buat Permission Baru</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Nama Permission</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST"
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
