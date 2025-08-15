@extends('layouts.rt')

@section('page-title', 'Detail Pembayaran - ' . $petugas->name)
@section('back-url', url()->previous())
@section('content')

    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <div>
                <h3 class="text-primary mb-1">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Detail Pembayaran
                </h3>
                <p class="text-muted mb-0">
                    Petugas: <strong>{{ $petugas->name }}</strong>
                </p>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm mb-4 px-3 rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>

        @if ($pembayarans->isEmpty())
            <!-- Jika tidak ada data -->
            <div class="text-center py-5">
                <i class="fas fa-receipt text-muted" style="font-size: 4rem; opacity: 0.2;"></i>
                <h5 class="text-muted mt-3">Tidak ada pembayaran ditemukan</h5>
                <p class="text-muted small">Petugas ini belum melakukan penerimaan pembayaran.</p>
            </div>
        @else
            <!-- Loop per tanggal -->
            @foreach ($pembayarans as $tanggal => $items)
                @php
                    $carbonDate = \Carbon\Carbon::parse($tanggal);
                @endphp

                <!-- Judul Tanggal -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-gradient-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ $carbonDate->format('l, d M Y') }}
                        </h5>
                        <small>{{ $items->count() }} transaksi hari ini</small>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 10%;">Waktu</th>
                                    <th style="width: 10%;">RT</th>
                                    <th style="width: 15%;">Rumah</th>
                                    <th style="width: 15%;">Total</th>
                                    <th style="width: 12%;">Metode</th>
                                    <th style="width: 13%;">Status Setoran</th>
                                    <th class="text-center" style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $bayar)
                                    <tr>
                                        <td class="text-center text-muted">
                                            <small>{{ $bayar->created_at->format('H:i:s') }}</small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                                                {{ $bayar->rt->name ?? 'RT-' }}
                                            </span>
                                        </td>
                                        <td><strong>{{ $bayar->house_id }}</strong></td>
                                        <td class="fw-bold text-dark">
                                            Rp {{ number_format($bayar->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $bayar->payment_method == 'cash' ? 'success' : 'info' }} px-2 py-1">
                                                <i
                                                    class="fas fa-{{ $bayar->payment_method == 'cash' ? 'money-bill-wave' : 'credit-card' }} me-1"></i>
                                                {{ ucfirst($bayar->payment_method) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($bayar->setoran_id)
                                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i> Sudah
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                                    <i class="fas fa-clock me-1"></i> Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('manage-rt.shared.detail-pembayaran.show', $bayar->id) }}"
                                                class="btn btn-sm btn-outline-primary px-3 rounded-pill hover-scale"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye me-1"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Ringkasan -->
                    <div class="card-footer bg-light py-2">
                        <small class="text-muted">
                            Total hari ini:
                            <strong>
                                Rp {{ number_format($items->sum('total_amount'), 0, ',', '.') }}
                            </strong> dari {{ $items->count() }} transaksi
                        </small>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

@endsection

@push('css')
    <style>
        /* Gradient Header */
        .bg-gradient-info {
            background: linear-gradient(90deg, #17a2b8, #138496);
            color: white;
        }

        /* Hover scale effect */
        .hover-scale:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        /* Badge styling */
        .badge.bg-success-subtle {
            background-color: #d1f7e5;
            color: #0a7a4a;
        }

        .badge.bg-danger-subtle {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Table hover */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Responsive untuk mobile */
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
        // Efek halus saat masuk halaman
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector('.container')?.classList.add('animate__animated', 'animate__fadeIn');
        });
    </script>
@endpush
