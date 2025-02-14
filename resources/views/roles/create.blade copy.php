@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Buat Role Baru</h1>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Role</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label for="permissions">Permissions</label><br>
                @foreach ($permissions as $permission)
                    <label>
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"> {{ $permission->name }}
                    </label><br>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan Role</button>
        </form>
    </div>
@endsection
