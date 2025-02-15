@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ isset($pembayaran) ? 'Edit Pembayaran' : 'Tambah Pembayaran' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('manage-rt.pembayaran.index') }}">Kembali</a>
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

    <form
        action="{{ isset($pembayaran) ? route('manage-rt.pembayaran.update', $pembayaran->id) : route('manage-rt.pembayaran.store') }}"
        method="POST">
        @csrf
        @if (isset($pembayaran))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="house_id">Rumah:</label>
            <select name="house_id" class="form-control" required>
                <!-- Daftar rumah bisa diambil dari database -->
                <option value="">Pilih Rumah</option>
                @foreach ($houses as $rumah)
                    <option value="{{ $rumah->id }}" @if (isset($pembayaran) && $pembayaran->house_id == $rumah->id) selected @endif>
                        {{ $rumah->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="total_amount">Jumlah Pembayaran:</label>
            <input type="number" name="total_amount" class="form-control"
                value="{{ old('total_amount', isset($pembayaran) ? $pembayaran->total_amount : '') }}" required
                step="0.01" min="0">
        </div>

        <div class="form-group">
            <label for="payment_method">Metode Pembayaran:</label>
            <select name="payment_method" class="form-control" required>
                <option value="manual"
                    {{ old('payment_method', isset($pembayaran) ? $pembayaran->payment_method : '') == 'manual' ? 'selected' : '' }}>
                    Manual</option>
                <option value="midtrans"
                    {{ old('payment_method', isset($pembayaran) ? $pembayaran->payment_method : '') == 'midtrans' ? 'selected' : '' }}>
                    Midtrans</option>
                <option value="xendit"
                    {{ old('payment_method', isset($pembayaran) ? $pembayaran->payment_method : '') == 'xendit' ? 'selected' : '' }}>
                    Xendit</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" class="form-control" required>
                <option value="confirmed"
                    {{ old('status', isset($pembayaran) ? $pembayaran->status : '') == 'confirmed' ? 'selected' : '' }}>
                    Confirmed</option>
                <option value="failed"
                    {{ old('status', isset($pembayaran) ? $pembayaran->status : '') == 'failed' ? 'selected' : '' }}>Failed
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="collector_id">Petugas Penerima:</label>
            <select name="collector_id" class="form-control">
                <option value="">- Pilih Petugas -</option>
                <!-- Daftar petugas bisa diambil dari tabel users -->
            </select>
        </div>

        <button type="submit"
            class="btn btn-primary">{{ isset($pembayaran) ? 'Update Pembayaran' : 'Simpan Pembayaran' }}</button>
    </form>
@endsection
