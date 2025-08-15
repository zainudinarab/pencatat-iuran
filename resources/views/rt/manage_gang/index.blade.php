@extends('layouts.rt')

@section('page-title', 'Daftar Gang')
@section('back-url', null)
@section('content')

    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary mb-0">
                <i class="fas fa-road me-2"></i>Daftar Gang
            </h3>
            <a href="{{ route('manage-rt.gangs.create') }}"
                class="btn btn-success btn-lg px-4 d-flex align-items-center shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Tambah Gang
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
                            <th style="width: 30%;">Nama Gang</th>
                            <th style="width: 20%;">RT</th>
                            <th style="width: 25%;">Ketua Gang</th>
                            <th class="text-center" style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gangs as $gang)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $gang->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                        RT {{ $gang->rt->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-info text-white me-3"
                                            style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $gang->ketuaGang->name ?? 'Tidak ada' }}</strong>
                                            <br>
                                            <small class="text-muted">Ketua Gang</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('manage-rt.gangs.edit', $gang->id) }}"
                                            class="btn btn-sm btn-outline-primary px-3 d-flex align-items-center hover-scale"
                                            title="Edit Gang">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('manage-rt.gangs.destroy', $gang->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger px-3 d-flex align-items-center hover-scale"
                                                title="Hapus Gang"
                                                onclick="return confirm('Yakin ingin menghapus Gang ini? Semua data terkait akan terpengaruh.')">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-road text-muted" style="font-size: 3rem; opacity: 0.2;"></i>
                                    <h5 class="text-muted mt-3">Tidak ada gang ditemukan</h5>
                                    <p class="text-muted small">Belum ada data gang yang ditambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($gangs->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $gangs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

        <!-- Footer Khusus -->
        <div class="text-center mt-4">
            <p class="text-muted small">
                <i class="fas fa-code me-1"></i> Dikelola oleh sistem RT | <strong>Bay: arnet</strong>
            </p>
        </div>
    </div>

@endsection

@push('css')
    <style>
        /* Icon circle */
        .icon-circle {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        /* Hover effect */
        .hover-scale {
            transition: all 0.2s ease;
        }

        .hover-scale:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Table hover */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Badge styling */
        .badge {
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn {
                width: 100% !important;
            }

            .icon-circle {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }

            .table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        // Auto-dismiss alert setelah 5 detik
        document.addEventListener("DOMContentLoaded", function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            }
        });
    </script>
@endpush
