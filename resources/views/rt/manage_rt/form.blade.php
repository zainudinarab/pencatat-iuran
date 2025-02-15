@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ isset($rt) ? 'Edit RT' : 'Tambah RT' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('manage-rt.rts.index') }}">Kembali</a>
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

    <form action="{{ isset($rt) ? route('manage-rt.rts.update', $rt->id) : route('manage-rt.rts.store') }}" method="POST">
        @csrf
        @if (isset($rt))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Nama RT:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', isset($rt) ? $rt->name : '') }}"
                required>
        </div>

        <div class="form-group">
            <label for="ketua_rt_id">Ketua RT:</label>
            <select name="ketua_rt_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ isset($rt) && $rt->ketua_rt_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="bendahara_id">Bendahara:</label>
            <select name="bendahara_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ isset($rt) && $rt->bendahara_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="rw">RW:</label>
            <input type="text" name="rw" class="form-control" value="{{ old('rw', isset($rt) ? $rt->rw : '') }}">
        </div>

        <div class="form-group">
            <label for="village">Desa/Kelurahan:</label>
            <input type="text" name="village" class="form-control"
                value="{{ old('village', isset($rt) ? $rt->village : '') }}">
        </div>

        <div class="form-group">
            <label for="district">Kecamatan:</label>
            <input type="text" name="district" class="form-control"
                value="{{ old('district', isset($rt) ? $rt->district : '') }}">
        </div>

        <div class="form-group">
            <label for="city">Kota/Kabupaten:</label>
            <input type="text" name="city" class="form-control"
                value="{{ old('city', isset($rt) ? $rt->city : '') }}">
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($rt) ? 'Update RT' : 'Simpan RT' }}</button>
    </form>
@endsection
