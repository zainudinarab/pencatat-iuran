<!-- resources/views/pengeluaran/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Pengeluaran</h1>

        <form action="{{ route('pengeluaran.update', $pengeluaran) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="amount">Jumlah Pengeluaran</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01"
                    value="{{ $pengeluaran->amount }}" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <input type="text" name="description" id="description" class="form-control"
                    value="{{ $pengeluaran->description }}" required>
            </div>
            <div class="form-group">
                <label for="tanggal_pengeluaran">Tanggal Pengeluaran</label>
                <input type="date" name="tanggal_pengeluaran" id="tanggal_pengeluaran" class="form-control"
                    value="{{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('Y-m-d') }}" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
@endsection
