@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ isset($iuranWajib) ? 'Edit Iuran Wajib' : 'Tambah Iuran Wajib' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('manage-rt.iuran-wajib.index') }}">Kembali</a>
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
        action="{{ isset($iuranWajib) ? route('manage-rt.iuran-wajib.update', $iuranWajib->id) : route('manage-rt.iuran-wajib.store') }}"
        method="POST">
        @csrf
        @if (isset($iuranWajib))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="rt_id">RT:</label>
            <select name="rt_id" class="form-control" required>
                @foreach ($rts as $rt)
                    <option value="{{ $rt->id }}"
                        {{ isset($iuranWajib) && $iuranWajib->rt_id == $rt->id ? 'selected' : '' }}>{{ $rt->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="bill_month">Bulan Tagihan (YYYYMM):</label>
            <input type="text" name="bill_month" class="form-control"
                value="{{ old('bill_month', isset($iuranWajib) ? $iuranWajib->bill_month : '') }}" required maxlength="6">
        </div>

        <div class="form-group">
            <label for="name">Nama Iuran:</label>
            <input type="text" name="name" class="form-control"
                value="{{ old('name', isset($iuranWajib) ? $iuranWajib->name : '') }}" required>
        </div>

        <div class="form-group">
            <label for="amount">Jumlah:</label>
            <input type="number" name="amount" class="form-control"
                value="{{ old('amount', isset($iuranWajib) ? $iuranWajib->amount : '') }}" required step="0.01"
                min="0">
        </div>

        <button type="submit"
            class="btn btn-primary">{{ isset($iuranWajib) ? 'Update Iuran Wajib' : 'Simpan Iuran Wajib' }}</button>
    </form>
@endsection
