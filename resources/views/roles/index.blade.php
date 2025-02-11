@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Role</h1>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">Buat Role Baru</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Nama Role</th>
                    <th>Permissions</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach ($role->permissions as $permission)
                                {{ $permission->name }}<br>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
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
