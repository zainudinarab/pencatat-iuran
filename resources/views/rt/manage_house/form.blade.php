@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ isset($house) ? 'Edit Rumah' : 'Tambah Rumah' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('manage-rt.houses.index') }}">Kembali</a>
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

    <form action="{{ isset($house) ? route('manage-rt.houses.update', $house->id) : route('manage-rt.houses.store') }}"
        method="POST">
        @csrf
        @if (isset($house))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="id">ID Rumah:</label>
            <input type="text" name="id" class="form-control"
                value="{{ old('id', isset($house) ? $house->id : '') }}" required>
        </div>

        <div class="form-group">
            <label for="blok">Blok:</label>
            <input type="text" name="blok" class="form-control"
                value="{{ old('blok', isset($house) ? $house->blok : '') }}" required maxlength="1">
        </div>

        <div class="form-group">
            <label for="nomer">Nomer:</label>
            <input type="number" name="nomer" class="form-control"
                value="{{ old('nomer', isset($house) ? $house->nomer : '') }}" required>
        </div>

        <div class="form-group">
            <label for="rt_id">RT:</label>
            <select name="rt_id" class="form-control" required>
                @foreach ($rts as $rt)
                    <option value="{{ $rt->id }}" {{ isset($house) && $house->rt_id == $rt->id ? 'selected' : '' }}>
                        {{ $rt->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="address">Alamat:</label>
            <input type="text" name="address" class="form-control"
                value="{{ old('address', isset($house) ? $house->address : '') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($house) ? 'Update Rumah' : 'Simpan Rumah' }}</button>
    </form>
@endsection
