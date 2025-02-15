@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Tambah Pengeluaran RT</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('manage-rt.pengeluaran-rt.index') }}">Kembali</a>
            </div>
        </div>
    </div>

    <form action="{{ route('manage-rt.pengeluaran-rt.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="rt_id">RT:</label>
            <select name="rt_id" class="form-control" required>
                <option value="">- Pilih RT -</option>
                @foreach ($rts as $rt)
                    <option value="{{ $rt->id }}" {{ old('rt_id') == $rt->id ? 'selected' : '' }}>{{ $rt->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="amount">Jumlah Pengeluaran:</label>
            <input type="number" name="amount" class="form-control" required step="0.01" min="0">
        </div>

        <div class="form-group">
            <label for="description">Keterangan:</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label for="approved_by">Bendahara yang Menyetujui:</label>
            <select name="approved_by" class="form-control" required>
                <option value="">- Pilih Bendahara -</option>
                @foreach ($bendaharas as $bendahara)
                    <option value="{{ $bendahara->id }}" {{ old('approved_by') == $bendahara->id ? 'selected' : '' }}>
                        {{ $bendahara->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Pengeluaran</button>
    </form>
@endsection
