@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ isset($setoran) ? 'Edit Setoran Petugas' : 'Tambah Setoran Petugas' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('manage-rt.setoran.index') }}">Kembali</a>
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

    <form action="{{ isset($setoran) ? route('manage-rt.setoran.update', $setoran->id) : route('manage-rt.setoran.store') }}"
        method="POST">
        @csrf
        @if (isset($setoran))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="collector_id">Petugas Penyetor:</label>
            <select name="collector_id" class="form-control" required>
                <option value="">- Pilih Petugas Penyetor -</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ old('collector_id', isset($setoran) ? $setoran->collector_id : '') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="rt_id">RT Tujuan:</label>
            <select name="rt_id" class="form-control" required>
                <option value="">- Pilih RT -</option>
                @foreach ($rts as $rt)
                    <option value="{{ $rt->id }}"
                        {{ old('rt_id', isset($setoran) ? $setoran->rt_id : '') == $rt->id ? 'selected' : '' }}>
                        {{ $rt->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="total_amount">Jumlah Setoran:</label>
            <input type="number" name="total_amount" class="form-control"
                value="{{ old('total_amount', isset($setoran) ? $setoran->total_amount : '') }}" required step="0.01"
                min="0">
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" class="form-control" required>
                <option value="pending"
                    {{ old('status', isset($setoran) ? $setoran->status : '') == 'pending' ? 'selected' : '' }}>Pending
                </option>
                <option value="confirmed"
                    {{ old('status', isset($setoran) ? $setoran->status : '') == 'confirmed' ? 'selected' : '' }}>Confirmed
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="approved_by">Bendahara yang Menyetujui:</label>
            <select name="approved_by" class="form-control">
                <option value="">- Pilih Bendahara -</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ old('approved_by', isset($setoran) ? $setoran->approved_by : '') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($setoran) ? 'Update Setoran' : 'Simpan Setoran' }}</button>
    </form>
@endsection
