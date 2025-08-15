@extends('layouts.rt')

@section('page-title', 'Detail Rincian Pembayaran - Rumah ' . $pembayaran->house_id)
@section('back-url', url()->previous())
@section('content')

    <div class="container py-4">
        <!-- Header -->
        <div class="row g-3 align-items-start mb-4">
            <div class="col-md-8">
                <h3 class="text-primary mb-1">
                    <i class="fas fa-receipt me-2"></i>Detail Rincian Pembayaran
                </h3>
                <p class="text-muted mb-0">
                    <strong>Rumah:</strong> {{ $pembayaran->house_id }} |
                    <strong>Tanggal:</strong> {{ $pembayaran->created_at->format('d M Y, H:i') }}
                </p>
                @if ($pembayaran->collector)
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mt-2">
                        <i class="fas fa-user-tie me-1"></i> Petugas: {{ $pembayaran->collector->name }}
                    </span>
                @endif
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Ringkasan Total -->
        @php
            $total = $pembayaran->detailPembayarans->sum('amount');
        @endphp
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Total Pembayaran</h5>
                    <p class="text-muted mb-0">Jumlah item: {{ $pembayaran->detailPembayarans->count() }}</p>
                </div>
                <div class="display-6 fw-bold text-success">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Tabel Rincian -->
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 30%;">Iuran</th>
                            <th style="width: 20%;">Bulan</th>
                            <th style="width: 15%;">Rumah</th>
                            <th style="width: 20%;">Jumlah</th>
                            <th class="text-center" style="width: 15%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayaran->detailPembayarans as $detail)
                            <tr>
                                <td>
                                    <strong>{{ $detail->iuranWajib->jenisIuran->name ?? 'Iuran Tidak Diketahui' }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        ID: {{ $detail->iuran_wajib_id ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    @if ($detail->iuranWajib)
                                        {{ \Carbon\Carbon::createFromFormat('Ym', $detail->iuranWajib->bill_month)->translatedFormat('F Y') }}
                                    @else
                                        <em class="text-muted">-</em>
                                    @endif
                                </td>
                                <td><code>{{ $detail->house_id }}</code></td>
                                <td class="fw-bold text-dark">
                                    Rp {{ number_format($detail->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if ($detail->status == 'confirmed')
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                            <i class="fas fa-check-circle me-1"></i> Terverifikasi
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                            <i class="fas fa-clock me-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-box-open text-muted" style="font-size: 3rem; opacity: 0.2;"></i>
                                    <h5 class="text-muted mt-3">Tidak ada rincian pembayaran</h5>
                                    <p class="text-muted small">Transaksi ini belum memiliki detail iuran.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="card-footer bg-light py-2">
                <small class="text-muted">
                    Metode: <strong>{{ ucfirst($pembayaran->payment_method) }}</strong> |
                    ID Transaksi: <code>{{ $pembayaran->id }}</code>
                </small>
            </div>
        </div>
    </div>

@endsection

@push('css')
    <style>
        /* Badge styling */
        .badge.bg-success-subtle {
            background-color: #d1f7e5;
            color: #0a7a4a;
            font-weight: 500;
        }

        .badge.bg-warning-subtle {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Table hover */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        /* Code style */
        code {
            background-color: #f0f0f0;
            padding: 0.2em 0.4em;
            border-radius: 4px;
            font-size: 0.9em;
            font-family: monospace;
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
                min-width: 100px;
            }

            .card-body {
                flex-direction: column;
                text-align: center;
            }

            .display-6 {
                font-size: 1.8rem;
                margin-top: 0.5rem;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        // Efek animasi masuk (opsional)
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.querySelector('.container');
            if (container) {
                container.classList.add('animate__animated', 'animate__fadeIn');
            }
        });
    </script>
@endpush
