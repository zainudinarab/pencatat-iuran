@extends('layouts.rt') {{-- Pastikan sesuai layout kamu, misal: rt atau app --}}

@section('page-title', isset($house) ? 'Edit Rumah' : 'Tambah Rumah Baru')
@section('back-url', route('manage-rt.houses.index'))
@section('content')

    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary mb-0">
                <i class="fas fa-home me-2"></i>
                {{ isset($house) ? 'Edit Rumah' : 'Tambah Rumah Baru' }}
            </h3>
            <a href="{{ route('manage-rt.houses.index') }}"
                class="btn btn-outline-secondary btn-lg px-4 d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <!-- Card Form -->
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-4">
                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Terjadi kesalahan input:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            document.querySelector('.alert-danger')?.scrollIntoView({
                                behavior: 'smooth'
                            });
                        });
                    </script>
                @endif

                <!-- Form -->
                <form
                    action="{{ isset($house) ? route('manage-rt.houses.update', $house->id) : route('manage-rt.houses.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($house))
                        @method('PUT')
                    @endif

                    <div class="row g-4">
                        <!-- ID Rumah -->
                        <div class="col-md-6">
                            <label for="id" class="form-label">
                                <i class="fas fa-id-card me-1 text-muted"></i> ID Rumah
                            </label>
                            <input type="text" name="id" id="id"
                                class="form-control form-control-lg @error('id') is-invalid @enderror"
                                placeholder="Contoh: A01" value="{{ old('id', $house->id ?? '') }}" required maxlength="3"
                                pattern="[A-Za-z]{1}[0-9]{2}" title="Format: 1 huruf + 2 angka (contoh: A01)">
                            <div class="form-text">Format: 1 huruf + 2 angka (contoh: A01)</div>
                            @error('id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Blok -->
                        <div class="col-md-6">
                            <label for="blok" class="form-label">
                                <i class="fas fa-building me-1 text-muted"></i> Blok
                            </label>
                            <input type="text" name="blok" id="blok"
                                class="form-control form-control-lg @error('blok') is-invalid @enderror"
                                placeholder="A, B, C" value="{{ old('blok', $house->blok ?? '') }}" required maxlength="1"
                                pattern="[A-Za-z]" title="Harus 1 huruf (A-Z)">
                            <div class="form-text">Masukkan 1 huruf (A, B, C, ...)</div>
                            @error('blok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomer -->
                        <div class="col-md-6">
                            <label for="nomer" class="form-label">
                                <i class="fas fa-hashtag me-1 text-muted"></i> Nomor Rumah
                            </label>
                            <input type="number" name="nomer" id="nomer"
                                class="form-control form-control-lg @error('nomer') is-invalid @enderror"
                                placeholder="1, 2, 3, ..." value="{{ old('nomer', $house->nomer ?? '') }}" required
                                min="1" max="999">
                            @error('nomer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- RT -->
                        <div class="col-md-6">
                            <label for="rt_id" class="form-label">
                                <i class="fas fa-users me-1 text-muted"></i> RT
                            </label>
                            <select name="rt_id" id="rt_id"
                                class="form-select form-select-lg @error('rt_id') is-invalid @enderror" required>
                                <option value="" disabled {{ old('rt_id', $house->rt_id ?? '') ? '' : 'selected' }}>
                                    Pilih RT</option>
                                @foreach ($rts as $rt)
                                    <option value="{{ $rt->id }}"
                                        {{ old('rt_id', $house->rt_id ?? '') == $rt->id ? 'selected' : '' }}>
                                        RT {{ $rt->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rt_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="col-md-12">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1 text-muted"></i> Alamat Lengkap
                            </label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3"
                                placeholder="Contoh: Jl. Kenanga No. 10, Kel. Mekar Jaya" required>{{ old('address', $house->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit"
                            class="btn btn-primary btn-lg px-5 d-flex align-items-center shadow-sm hover-scale">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($house) ? 'Perbarui Rumah' : 'Simpan Rumah' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-muted small">
                <i class="fas fa-lock me-1"></i>
                ID Rumah bersifat unik dan tidak bisa diubah setelah dibuat (jika sudah digunakan).
            </p>
        </div>
    </div>

@endsection

@push('css')
    <style>
        /* Hover effect */
        .hover-scale {
            transition: all 0.2s ease;
        }

        .hover-scale:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Form label icon */
        .form-label i {
            opacity: 0.7;
            font-size: 0.9rem;
        }

        /* Card styling */
        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }

            .form-label {
                font-size: 0.95rem;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        // Fokus ke input pertama
        document.addEventListener("DOMContentLoaded", function() {
            const firstInput = document.querySelector('input, select, textarea');
            if (firstInput) firstInput.focus();
        });
    </script>
@endpush
