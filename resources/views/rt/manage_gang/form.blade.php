@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ isset($gang) ? 'Edit Gang' : 'Tambah Gang' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('manage-rt.gangs.index') }}">Kembali</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($gang) ? route('manage-rt.gangs.update', $gang->id) : route('manage-rt.gangs.store') }}"
        method="POST">
        @csrf
        @if (isset($gang))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Nama Gang:</label>
            <input type="text" name="name" class="form-control"
                value="{{ old('name', isset($gang) ? $gang->name : '') }}" required>
        </div>

        <div class="form-group">
            <label for="rt_id">RT:</label>
            <select name="rt_id" class="form-control" required>
                @foreach ($rts as $rt)
                    <option value="{{ $rt->id }}" {{ isset($gang) && $gang->rt_id == $rt->id ? 'selected' : '' }}>
                        {{ $rt->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="ketua_gang_id">Ketua Gang:</label>
            <select name="ketua_gang_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ isset($gang) && $gang->ketua_gang_id == $user->id ? 'selected' : '' }}>{{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($gang) ? 'Update Gang' : 'Simpan Gang' }}</button>
    </form>
@endsection
