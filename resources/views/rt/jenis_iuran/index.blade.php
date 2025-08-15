@extends('layouts.rt')

@section('page-title', 'Jenis Iuran')
@section('back-url', null)
@section('content')

    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary mb-0">
                <i class="fas fa-coins me-2"></i>Jenis Iuran Wajib
            </h3>
            <a href="{{ route('manage-rt.jenis-iuran.create') }}"
                class="btn btn-primary btn-lg px-4 d-flex align-items-center shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Tambah Jenis Iuran
            </a>
        </div>

        <!-- Alert Sukses -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tabel -->
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 45%;">Nama Jenis Iuran</th>
                            <th class="text-end" style="width: 20%;">Jumlah</th>
                            <th class="text-center" style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jenisIurans as $jenisIuran)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-warning text-white me-3"
                                            style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $jenisIuran->name }}</strong>
                                            <br>
                                            <small class="text-muted">ID: {{ $jenisIuran->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    Rp {{ number_format($jenisIuran->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('manage-rt.jenis-iuran.edit', $jenisIuran->id) }}"
                                            class="btn btn-sm btn-outline-warning px-3 d-flex align-items-center hover-scale"
                                            title="Edit">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('manage-rt.jenis-iuran.destroy', $jenisIuran->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger px-3 d-flex align-items-center hover-scale"
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus jenis iuran ini? Semua tagihan terkait akan terpengaruh.')">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-coins text-muted" style="font-size: 3rem; opacity: 0.2;"></i>
                                    <h5 class="text-muted mt-3">Tidak ada jenis iuran ditemukan</h5>
                                    <p class="text-muted small">Belum ada jenis iuran yang ditambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($jenisIurans->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $jenisIurans->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Kelola jenis iuran wajib seperti kebersihan, keamanan, dll dengan jumlah tetap.
            </p>
        </div>
    </div>

@endsection

@push('css')
    <style>
        .icon-circle {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .hover-scale {
            transition: all 0.2s ease;
        }

        .hover-scale:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                setTimeout(() => {
                    new bootstrap.Alert(alert).close();
                }, 5000);
            }
        });
    </script>
@endpush
