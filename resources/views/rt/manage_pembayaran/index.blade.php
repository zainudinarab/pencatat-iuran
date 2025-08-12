@extends('layouts.rt')

@section('page-title', 'Daftar Pembayaran')
@section('back-url', null)
@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-money-bill-wave me-2"></i>Daftar Pembayaran
            </h5>
            <div>
                @can('create', App\Models\Pembayaran::class)
                    <a href="{{ route('manage-rt.pembayaran.create') }}" class="btn btn-light btn-sm d-flex align-items-center">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Pembayaran
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Alert -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabel Responsif -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Rumah</th>
                            <th>Jumlah</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
                            <th>Petugas Penerima</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayaran as $index => $pembayaranItem)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $pembayaranItem->house_id }}</strong></td>
                                <td>
                                    <span class="badge bg-success-subtle text-success fw-medium">
                                        Rp {{ number_format($pembayaranItem->total_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-white">
                                        {{ ucfirst($pembayaranItem->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($pembayaranItem->status === 'lunas')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Lunas
                                        </span>
                                    @elseif ($pembayaranItem->status === 'dibatalkan')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Dibatalkan
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>{{ ucfirst($pembayaranItem->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($pembayaranItem->collector)
                                        {{ $pembayaranItem->collector->name }}
                                    @else
                                        <span class="text-muted fst-italic">Belum ditugaskan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('update', $pembayaranItem)
                                            <a href="{{ route('manage-rt.pembayaran.edit', $pembayaranItem->id) }}"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $pembayaranItem)
                                            <form action="{{ route('manage-rt.pembayaran.destroy', $pembayaranItem->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus pembayaran ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox me-1"></i> Belum ada data pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($pembayaran->hasPages())
                <div class="m-3">
                    {{ $pembayaran->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

@endsection

@push('css')
    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge.bg-success-subtle {
            background-color: #d1f7e5;
            font-size: 0.95rem;
        }

        .badge.bg-info {
            background-color: #17a2b8;
            color: white !important;
        }

        .btn-group .btn {
            transition: all 0.2s ease;
        }

        .btn-group .btn:hover {
            transform: scale(1.05);
        }
    </style>
@endpush

@push('js')
    <script>
        // Aktifkan tooltip
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
