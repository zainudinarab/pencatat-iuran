@extends('layouts.rt') {{-- Pastikan sesuai dengan layout kamu, misal: rt atau app --}}

@section('page-title', isset($gang) ? 'Edit Gang' : 'Tambah Gang Baru')
@section('back-url', route('manage-rt.gangs.index'))
@section('content')

    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary mb-0">
                <i class="fas fa-road me-2"></i>
                {{ isset($gang) ? 'Edit Gang' : 'Tambah Gang Baru' }}
            </h3>
            <a href="{{ route('manage-rt.gangs.index') }}"
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
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <script>
                        // Auto-scroll ke bagian alert
                        document.addEventListener("DOMContentLoaded", function() {
                            document.querySelector('.alert-danger')?.scrollIntoView({
                                behavior: 'smooth'
                            });
                        });
                    </script>
                @endif

                <!-- Form -->
                <form
                    action="{{ isset($gang) ? route('manage-rt.gangs.update', $gang->id) : route('manage-rt.gangs.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($gang))
                        @method('PUT')
                    @endif

                    <div class="row g-4">
                        <!-- Nama Gang -->
                        <div class="col-md-12">
                            <label for="name" class="form-label">
                                <i class="fas fa-sign me-1 text-muted"></i> Nama Gang
                            </label>
                            <input type="text" name="name" id="name"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                placeholder="Contoh: Gang Mawar, Gang Melati" value="{{ old('name', $gang->name ?? '') }}"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- RT -->
                        <div class="col-md-6">
                            <label for="rt_id" class="form-label">
                                <i class="fas fa-home me-1 text-muted"></i> RT
                            </label>
                            <select name="rt_id" id="rt_id"
                                class="form-select form-select-lg @error('rt_id') is-invalid @enderror" required>
                                <option value="" disabled {{ old('rt_id', $gang->rt_id ?? '') ? '' : 'selected' }}>
                                    Pilih RT</option>
                                @foreach ($rts as $rt)
                                    <option value="{{ $rt->id }}"
                                        {{ old('rt_id', $gang->rt_id ?? '') == $rt->id ? 'selected' : '' }}>
                                        RT {{ $rt->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rt_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ketua Gang -->
                        <div class="col-md-6">
                            <label for="ketua_gang_id" class="form-label">
                                <i class="fas fa-user-shield me-1 text-muted"></i> Ketua Gang
                            </label>
                            <select name="ketua_gang_id" id="ketua_gang_id"
                                class="form-select form-select-lg @error('ketua_gang_id') is-invalid @enderror" required>
                                <option value="" disabled
                                    {{ old('ketua_gang_id', $gang->ketua_gang_id ?? '') ? '' : 'selected' }}>Pilih Ketua
                                    Gang</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('ketua_gang_id', $gang->ketua_gang_id ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->house_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('ketua_gang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit"
                            class="btn btn-primary btn-lg px-5 d-flex align-items-center shadow-sm hover-scale">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($gang) ? 'Perbarui Gang' : 'Simpan Gang' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Pastikan data diisi dengan benar. Ketua gang harus merupakan warga yang terdaftar.
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
        // Opsional: Fokus ke input pertama
        document.addEventListener("DOMContentLoaded", function() {
            const firstInput = document.querySelector('input, select');
            if (firstInput) firstInput.focus();
        });
    </script>
@endpush
