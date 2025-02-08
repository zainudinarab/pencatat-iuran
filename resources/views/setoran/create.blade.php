@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tambah Setoran</h2>
        <form action="{{ route('setoran.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="bendahara_id" class="form-label">Bendahara</label>
                <select name="bendahara_id" class="form-control" required>
                    @foreach ($bendaharas as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="total_amount" class="form-label">Total Jumlah</label>
                <input type="number" name="total_amount" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_setoran" class="form-label">Tanggal Setoran</label>
                <input type="date" name="tanggal_setoran" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('setoran.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
