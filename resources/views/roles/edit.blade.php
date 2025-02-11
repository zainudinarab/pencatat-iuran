@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Role</h1>
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Role</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" required>
            </div>

            <div class="form-group mt-3">
                <label for="permissions">Permissions</label><br>
                @foreach ($permissions as $permission)
                    <label>
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                        {{ $permission->name }}
                    </label><br>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Role</button>
        </form>
    </div>
@endsection
