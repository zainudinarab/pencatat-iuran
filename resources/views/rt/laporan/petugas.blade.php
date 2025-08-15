@extends('layouts.rt')

@section('page-title', 'Laporan Setoran per Petugas')
@section('back-url') {{-- Sesuaikan route back --}}
@section('content')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 text-primary">
                    <i class="fas fa-chart-bar me-2"></i>Laporan Setoran Petugas per RT
                </h4>
                <p class="text-muted mb-0">Ringkasan penerimaan dan setoran per petugas di setiap RT</p>
            </div>
            <div>
                <a href="" class="btn btn-outline-success btn-sm px-3">
                    <i class="fas fa-file-export me-1"></i> Ekspor Excel
                </a>
            </div>
        </div>

        <!-- Statistik Ringkasan (Opsional) -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-start border-primary border-4 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total RT</h6>
                        <h3 class="text-primary mb-0">{{ $rts->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-start border-success border-4 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Setoran</h6>
                        <h3 class="text-success mb-0">
                            Rp {{ number_format($totalSetor ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-start border-warning border-4 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Belum Disetor</h6>
                        <h3 class="text-warning mb-0">
                            Rp {{ number_format($totalBelumSetor ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Utama -->
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-gradient-primary text-white">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 15%;">RT</th>
                            <th style="width: 20%;">Petugas</th>
                            <th class="text-end" style="width: 15%;">Total Penerimaan</th>
                            <th class="text-end" style="width: 15%;">Sudah Disetorkan</th>
                            <th class="text-end" style="width: 15%;">Belum Disetorkan</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rts as $rt)
                            @php
                                $petugasList = $rt->petugasList ?? collect();
                            @endphp

                            @if ($petugasList->isNotEmpty())
                                @foreach ($petugasList as $petugas)
                                    <tr class="align-middle">
                                        <td class="text-center text-muted fw-light">
                                            {{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                        <td>
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                                RT {{ $rt->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-circle bg-primary text-white me-3"
                                                    style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <div>
                                                    <strong
                                                        class="d-block">{{ $petugas->name ?? 'Petugas Tidak Diketahui' }}</strong>
                                                    <small class="text-muted">Petugas Keuangan</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            Rp {{ number_format($petugas->total_penerimaan ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end text-success fw-semibold">
                                            Rp {{ number_format($petugas->total_setor ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end text-warning fw-bold">
                                            Rp {{ number_format($petugas->total_belum_setor ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('manage-rt.shared.petugas.detail', $petugas->id) }}"
                                                class="btn btn-sm btn-outline-primary px-3 rounded-pill hover-scale"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                            RT {{ $rt->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td colspan="4" class="text-center text-muted fst-italic">
                                        <i class="fas fa-user-slash me-1"></i> Tidak ada petugas aktif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-secondary disabled rounded-pill px-3">
                                            <i class="fas fa-ban me-1"></i> N/A
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-center">
                                        <i class="fas fa-folder-open text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <h5 class="text-muted mt-3 mb-1">Tidak ada data RT</h5>
                                        <p class="text-muted small">Belum ada data RT yang tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer ringkasan -->
            <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan data dari {{ $rts->count() }} RT
                </small>
                <span class="badge bg-light text-dark border">
                    <i class="fas fa-sync-alt me-1"></i> Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}
                </span>
            </div>
        </div>
    </div>

@endsection

@push('css')
    <style>
        /* Gradient Header */
        .bg-gradient-primary {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
        }

        /* Hover scale effect */
        .hover-scale:hover {
            transform: scale(1.03);
            transition: all 0.2s ease;
        }

        /* Icon circle */
        .icon-circle {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Table hover */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        /* Badge styling */
        .badge {
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 0.75rem;
            }

            .table td {
                display: block;
                text-align: right;
                padding-left: 40%;
                position: relative;
                font-size: 0.95rem;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                width: 40%;
                text-align: left;
                font-weight: 600;
                color: #495057;
            }

            .btn {
                width: 100%;
                margin-top: 0.3rem;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        // Opsional: Tambahkan efek fade in saat halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector('.container')?.classList.add('animate__animated', 'animate__fadeIn');
        });
    </script>
@endpush
