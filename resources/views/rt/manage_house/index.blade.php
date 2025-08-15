@extends('layouts.rt')

@section('page-title', 'Daftar Rumah')
@section('back-url', null)
@section('content')

    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary mb-0">
                <i class="fas fa-home me-2"></i>Daftar Rumah
            </h3>
            <a href="{{ route('manage-rt.houses.create') }}"
                class="btn btn-success btn-lg px-4 d-flex align-items-center shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Tambah Rumah
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
                            <th style="width: 10%;">ID Rumah</th>
                            <th style="width: 8%;">Blok</th>
                            <th style="width: 8%;">No.</th>
                            <th style="width: 10%;">RT</th>
                            <th style="width: 12%;">Gang</th>
                            <th style="width: 18%;">Pemilik</th>
                            <th style="width: 18%;">Alamat</th>
                            <th class="text-center" style="width: 11%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($houses as $house)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td><code class="bg-light px-2 py-1 rounded">{{ $house->id }}</code></td>
                                <td><strong>{{ $house->blok }}</strong></td>
                                <td><strong>{{ $house->nomer }}</strong></td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                        RT {{ $house->rt->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                        {{ $house->gang->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-success text-white me-3"
                                            style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $house->name }}</strong>
                                            <br>
                                            <small class="text-muted">Pemilik</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">
                                    {{ Str::limit($house->address, 50) }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('manage-rt.houses.edit', $house->id) }}"
                                            class="btn btn-sm btn-outline-primary px-3 d-flex align-items-center hover-scale"
                                            title="Edit Rumah">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('manage-rt.houses.destroy', $house->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger px-3 d-flex align-items-center hover-scale"
                                                title="Hapus Rumah"
                                                onclick="return confirm('Yakin ingin menghapus rumah ini? Semua data terkait akan terpengaruh.')">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-home text-muted" style="font-size: 3rem; opacity: 0.2;"></i>
                                    <h5 class="text-muted mt-3">Tidak ada rumah ditemukan</h5>
                                    <p class="text-muted small">Belum ada data rumah yang ditambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($houses->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $houses->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

        <!-- Footer Khusus -->
        <div class="text-center mt-4">
            <p class="text-muted small">
                <i class="fas fa-building me-1"></i> Total: <strong>{{ $houses->total() }}</strong> rumah terdaftar |
                <strong>Bay: arnet</strong>
            </p>
        </div>
    </div>

@endsection

@push('css')
    <style>
        /* Code style */
        code {
            font-size: 0.9em;
            padding: 0.2em 0.4em;
            background-color: #f0f0f0;
            border-radius: 4px;
            font-family: monospace;
        }

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

            .table td:nth-child(8) {
                white-space: normal;
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
