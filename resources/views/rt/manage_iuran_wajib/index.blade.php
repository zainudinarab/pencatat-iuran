@extends('layouts.rt')

@section('page-title', 'Daftar Iuran Wajib')
@section('back-url', null)
@section('content')

    <div class="card shadow-sm border-0">
        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="fas fa-file-invoice me-2"></i>
                Daftar Iuran Wajib
            </h5>
            <div>
                <a href="{{ route('manage-rt.iuran-wajib.create') }}"
                    class="btn btn-light btn-sm d-flex align-items-center px-3 hover-scale">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Iuran
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body p-0">
            <!-- Alert -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 15%;">RT</th>
                            <th style="width: 20%;">Bulan Tagihan</th>
                            <th style="width: 25%;">Nama Iuran</th>
                            <th style="width: 20%;">Jumlah</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($iuranWajib as $iuran)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                        RT {{ $iuran->rt->name }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ \Carbon\Carbon::createFromFormat('Ym', $iuran->bill_month)->translatedFormat('F Y') }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-info text-white me-3"
                                            style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-coins fa-xs"></i>
                                        </div>
                                        {{ $iuran->jenisIuran->name }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-medium">
                                        Rp {{ number_format($iuran->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('manage-rt.iuran-wajib.edit', $iuran->id) }}"
                                            class="btn btn-sm btn-outline-primary px-2" data-bs-toggle="tooltip"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('manage-rt.iuran-wajib.destroy', $iuran->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2"
                                                data-bs-toggle="tooltip" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus iuran wajib ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-file-invoice me-1"></i>
                                    Belum ada iuran wajib yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($iuranWajib->hasPages())
                <div class="m-3">
                    {{ $iuranWajib->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-3">
        <p class="text-muted small">
            <i class="fas fa-info-circle me-1"></i>
            Bay: arnet
        </p>
    </div>

@endsection

@push('css')
    <style>
        /* Hover scale */
        .hover-scale:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Icon circle */
        .icon-circle {
            font-size: 0.8rem;
        }

        /* Badge styling */
        .badge.bg-success-subtle {
            background-color: #d1f7e5;
            color: #0a7a4a;
        }

        .badge.bg-primary.bg-opacity-10 {
            background-color: #e7f3ff;
            color: #0d6efd;
        }

        /* Table hover */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Button group */
        .btn-group .btn {
            transition: all 0.2s ease;
        }

        .btn-group .btn:hover {
            transform: scale(1.08);
        }
    </style>
@endpush

@push('js')
    <script>
        // Tooltip
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipEl => new bootstrap.Tooltip(tooltipEl));
        });
    </script>
@endpush
