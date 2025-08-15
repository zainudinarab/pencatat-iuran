@extends('layouts.rt')

@section('page-title', 'Edit Jenis Iuran')
@section('back-url', route('manage-rt.jenis-iuran.index'))
@section('content')

    <!-- Judul -->
    <h4 class="mb-4 text-primary">
        <i class="fas fa-edit me-2"></i>Edit Jenis Iuran
    </h4>

    <!-- Card Form -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <!-- Alert Error -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Terdapat kesalahan pada input.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('manage-rt.jenis-iuran.update', $jenisIuran->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Iuran -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Jenis Iuran</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $jenisIuran->name) }}" placeholder="Contoh: Iuran Keamanan" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jumlah Iuran -->
                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="amount" id="amount"
                        class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount', $jenisIuran->amount) }}" placeholder="50000" min="0" step="100"
                        required>
                    <small class="text-muted">Masukkan nominal tanpa titik/koma</small>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('manage-rt.jenis-iuran.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('css')
    <style>
        .card {
            border-radius: 12px;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        }
    </style>
@endpush

@push('js')
    <script>
        // Fokus ke input nama saat halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('name');
            if (input) input.focus();
        });
    </script>
@endpush
