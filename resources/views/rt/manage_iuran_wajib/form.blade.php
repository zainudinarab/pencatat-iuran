@extends('layouts.rt')

@section('page-title', isset($iuranWajib) ? 'Edit Iuran Wajib' : 'Tambah Iuran Wajib')
@section('back-url', route('manage-rt.iuran-wajib.index'))
@section('content')

    <h4 class="mb-4 text-primary">
        <i class="fas fa-file-invoice me-2"></i>
        {{ isset($iuranWajib) ? 'Edit Iuran Wajib' : 'Tambah Iuran Wajib' }}
    </h4>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Mohon periksa kembali formulir Anda.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form
                action="{{ isset($iuranWajib) ? route('manage-rt.iuran-wajib.update', $iuranWajib->id) : route('manage-rt.iuran-wajib.store') }}"
                method="POST">
                @csrf
                @if (isset($iuranWajib))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    <!-- RT -->
                    <div class="col-md-6">
                        <label for="rt_id" class="form-label">RT</label>
                        <select name="rt_id" id="rt_id" class="form-select @error('rt_id') is-invalid @enderror"
                            required>
                            <option value="" disabled {{ old('rt_id', $iuranWajib->rt_id ?? '') ? '' : 'selected' }}>
                                Pilih RT</option>
                            @foreach ($rts as $rt)
                                <option value="{{ $rt->id }}"
                                    {{ old('rt_id', $iuranWajib->rt_id ?? '') == $rt->id ? 'selected' : '' }}>
                                    RT {{ $rt->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('rt_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Iuran -->
                    <div class="col-md-6">
                        <label for="jenis_iuran_id" class="form-label">Jenis Iuran</label>
                        <select name="jenis_iuran_id" id="jenis_iuran_id"
                            class="form-select @error('jenis_iuran_id') is-invalid @enderror" required>
                            <option value="" disabled
                                {{ old('jenis_iuran_id', $iuranWajib->jenis_iuran_id ?? '') ? '' : 'selected' }}>Pilih Jenis
                                Iuran</option>
                            @foreach ($jenisIuran as $jenis)
                                <option value="{{ $jenis->id }}" data-amount="{{ $jenis->amount }}"
                                    {{ old('jenis_iuran_id', $iuranWajib->jenis_iuran_id ?? '') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->name }} (Rp {{ number_format($jenis->amount, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_iuran_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bulan Mulai -->
                    <div class="col-md-6">
                        <label for="start_month" class="form-label">Bulan Mulai</label>
                        <select name="start_month" id="start_month"
                            class="form-select @error('start_month') is-invalid @enderror" required>
                            <option value="" disabled
                                {{ old('start_month', $iuranWajib->bill_month ?? '') ? '' : 'selected' }}>Pilih Bulan Mulai
                            </option>
                            @foreach ($months as $month)
                                <option value="{{ $month['value'] }}"
                                    {{ old('start_month', $iuranWajib->bill_month ?? '') == $month['value'] ? 'selected' : '' }}>
                                    {{ $month['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('start_month')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bulan Selesai -->
                    <div class="col-md-6">
                        <label for="end_month" class="form-label">Bulan Selesai (Opsional)</label>
                        <select name="end_month" id="end_month" class="form-select">
                            <option value="">Tidak berulang (hanya 1 bulan)</option>
                            @php
                                $selectedStart = old('start_month', $iuranWajib->bill_month ?? '');
                            @endphp
                            @foreach ($months as $month)
                                @if ($selectedStart && $month['value'] >= $selectedStart)
                                    <option value="{{ $month['value'] }}"
                                        {{ old('end_month') == $month['value'] ? 'selected' : '' }}>
                                        {{ $month['label'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <div class="form-text text-muted">Kosongkan jika hanya untuk satu bulan</div>
                    </div>

                    <!-- Jumlah (Auto-filled) -->
                    <div class="col-md-12">
                        <label for="amount" class="form-label">Jumlah (Rp)</label>
                        <input type="number" name="amount" id="amount"
                            class="form-control form-control-lg @error('amount') is-invalid @enderror" placeholder="10.000"
                            value="{{ old('amount', $iuranWajib->amount ?? '') }}" step="100" min="0" required>
                        <div class="form-text text-muted">
                            Jumlah akan terisi otomatis saat memilih jenis iuran.
                        </div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('manage-rt.iuran-wajib.index') }}" class="btn btn-outline-secondary px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i>
                        {{ isset($iuranWajib) ? 'Perbarui' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisIuranSelect = document.getElementById('jenis_iuran_id');
            const amountInput = document.getElementById('amount');

            // Isi otomatis saat pilih jenis iuran
            jenisIuranSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const amount = selectedOption.getAttribute('data-amount');
                if (amount) {
                    amountInput.value = amount;
                }
            });

            // Trigger change saat halaman dimuat (untuk edit)
            if (jenisIuranSelect.value) {
                jenisIuranSelect.dispatchEvent(new Event('change'));
            }

            // Update end_month saat start_month berubah
            document.getElementById('start_month').addEventListener('change', function() {
                const startMonth = this.value;
                const endMonthSelect = document.getElementById('end_month');
                const selectedEnd = endMonthSelect.value;

                // Reset options
                endMonthSelect.innerHTML = '<option value="">Tidak berulang (hanya 1 bulan)</option>';

                // Tambahkan opsi >= startMonth
                @foreach ($months as $month)
                    if ("{{ $month['value'] }}" >= startMonth) {
                        const option = document.createElement('option');
                        option.value = "{{ $month['value'] }}";
                        option.textContent = "{{ $month['label'] }}";
                        if ("{{ $month['value'] }}" === selectedEnd) {
                            option.selected = true;
                        }
                        endMonthSelect.appendChild(option);
                    }
                @endforeach
            });
        });
    </script>
@endpush
