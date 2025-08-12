@extends('layouts.rt')

@section('page-title', 'Daftar Setoran Petugas')
@section('back-url', null)
@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-receipt me-2"></i>Daftar Setoran Petugas
            </h5>
            <div>
                @can('create', App\Models\SetoranPetugas::class)
                    <a href="{{ route('manage-rt.setoran-petugas.create') }}"
                        class="btn btn-light btn-sm d-flex align-items-center">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Setoran
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body p-0">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Petugas Penyetor</th>
                            <th>RT Tujuan</th>
                            <th>Total Setoran</th>
                            <th>Status</th>
                            <th>Bendahara yang Menyetujui</th>
                            <th>Waktu Konfirmasi</th>
                            <th>Catatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($setoran as $index => $setoranItem)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $setoranItem->collector->name }}</strong></td>
                                <td>{{ $setoranItem->rt->name }}</td>
                                <td>
                                    <span class="badge bg-success-subtle text-success fw-medium">
                                        Rp {{ number_format($setoranItem->total_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($setoranItem->status === 'confirmed')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Disetujui
                                        </span>
                                    @elseif ($setoranItem->status === 'ditolak')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if (in_array($setoranItem->status, ['confirmed', 'ditolak']) && $setoranItem->approvedBy)
                                        <span
                                            class="text-{{ $setoranItem->status === 'confirmed' ? 'success' : 'danger' }} fw-medium">
                                            {{ $setoranItem->approvedBy->name }}
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">Menunggu konfirmasi</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($setoranItem->confirmed_at)
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($setoranItem->confirmed_at)->format('d M Y, H:i') }}
                                        </small>
                                    @else
                                        <span class="text-muted fst-italic">–</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($setoranItem->notes)
                                        <span class="text-dark" title="{{ $setoranItem->notes }}" data-bs-toggle="tooltip">
                                            {{ Str::limit($setoranItem->notes, 50) }}
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('update', $setoranItem)
                                            <a href="{{ route('manage-rt.setoran-petugas.edit', $setoranItem->id) }}"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $setoranItem)
                                            <form action="{{ route('manage-rt.setoran-petugas.destroy', $setoranItem->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus setoran ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox me-1"></i> Belum ada data setoran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($setoran->hasPages())
                <div class="m-3">
                    {{ $setoran->links('pagination::bootstrap-5') }}
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

        .btn-group .btn {
            transition: all 0.2s ease;
        }

        .btn-group .btn:hover {
            transform: scale(1.05);
        }

        td[title] {
            cursor: pointer;
        }
    </style>
@endpush

@push('js')
    <script>
        // Tooltip untuk catatan
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
