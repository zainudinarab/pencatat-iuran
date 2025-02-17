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
        <div class="mb-3">
            <label for="start_month" class="form-label">Bulan Mulai</label>
            <select class="form-control" id="start_month" name="start_month" required>
                @foreach ($months as $month)
                    <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="end_month" class="form-label">Bulan Selesai (Opsional)</label>
            <select class="form-control" id="end_month" name="end_month">
                <option value="">Pilih Bulan Selesai (jika lebih dari 1 bulan)</option>
                @foreach ($months as $month)
                    <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="jenis_iuran_id" class="form-label">Jenis Iuran</label>
            <select class="form-control" id="jenis_iuran_id" name="jenis_iuran_id" required>
                @foreach ($jenisIuran as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                @endforeach
            </select>
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
{{-- pus --}}
@push('js')
    <script>
        document.getElementById('start_month').addEventListener('change', function() {
            const startMonth = this.value;
            const endMonthSelect = document.getElementById('end_month');

            // Reset opsi end_month
            endMonthSelect.innerHTML = '<option value="">Pilih Bulan Selesai (jika lebih dari 1 bulan)</option>';

            // Tambahkan opsi yang valid
            @foreach ($months as $month)
                if ("{{ $month['value'] }}" >= startMonth) {
                    const option = document.createElement('option');
                    option.value = "{{ $month['value'] }}";
                    option.text = "{{ $month['label'] }}";
                    endMonthSelect.appendChild(option);
                }
            @endforeach
        });
    </script>
@endpush
