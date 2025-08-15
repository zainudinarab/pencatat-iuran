@extends('layouts.rt')

@section('page-title', 'Laporan Pembayaran Tahunan per Gang')
@section('back-url', route('manage-rt.dashboard')) {{-- Sesuaikan jika perlu --}}
@section('content')

    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="text-primary mb-1">
                    <i class="fas fa-chart-line me-2"></i>Laporan Pembayaran Tahunan per Gang
                </h3>
                <p class="text-muted mb-0">Rincian total pembayaran warga per gang untuk setiap tahun.</p>
            </div>
            <div>
                <a href="" class="btn btn-outline-success btn-sm px-3 rounded-pill">
                    <i class="fas fa-file-export me-1"></i> Ekspor Excel
                </a>
            </div>
        </div>

        @forelse ($data as $tahun => $items)
            @php
                $total = $totalPerTahun[$tahun] ?? 0;
            @endphp

            <!-- Card per Tahun -->
            <div class="card shadow-sm border-0 mb-4 animate__animated animate__fadeIn">
                <div
                    class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Tahun {{ $tahun }}
                    </h5>
                    <span class="badge bg-light text-white fs-6 px-4 py-2">
                        <i class="fas fa-coins me-1"></i>
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50%;">Gang</th>
                                <th style="width: 35%;">Total Pembayaran</th>
                                <th class="text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $row)
                                <tr>
                                    <td>
                                        <strong>{{ $gangs[$row->gang_id] ?? 'Gang Tidak Diketahui' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            ID: {{ $row->gang_id }}
                                        </small>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        Rp {{ number_format($row->total, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('manage-rt.shared.laporan.tahunan-gang.detail', ['tahun' => $tahun, 'gang_id' => $row->gang_id]) }}"
                                            class="btn btn-sm btn-outline-primary px-3 rounded-pill hover-scale"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer ringkasan -->
                <div class="card-footer bg-light py-2">
                    <small class="text-muted">
                        Menampilkan data dari <strong>{{ $items->count() }}</strong> gang.
                    </small>
                </div>
            </div>
        @empty
            <!-- Tidak ada data -->
            <div class="text-center py-5">
                <i class="fas fa-folder-open text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="text-muted mt-3">Tidak ada data laporan</h5>
                <p class="text-muted">Belum ada pembayaran yang tercatat untuk ditampilkan.</p>
            </div>
        @endforelse
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
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        /* Badge styling */
        .badge {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Table hover */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
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
                border-radius: 12px;
                padding: 0.75rem;
            }

            .table td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0;
                border-bottom: 1px dashed #e9ecef;
            }

            .table td:last-child {
                border-bottom: none;
            }

            .table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #495057;
                min-width: 120px;
            }

            .btn {
                width: 100%;
                margin-top: 0.3rem;
            }

            .card-header h5 {
                font-size: 1.1rem;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        // Efek animasi saat halaman dimuat (opsional)
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll('.animate__animated');
            cards.forEach((card, index) => {
                card.style.setProperty('--animate-delay', index * 0.1 + 's');
            });
        });
    </script>
@endpush
