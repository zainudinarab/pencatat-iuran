@extends('layouts.rt')

@section('page-title', "Detail Pembayaran - Tahun $tahun, Gang $gangName")
@section('back-url', route('manage-rt.shared.laporan.tahunan-gang'))
@section('content')

    <div class="container py-4">
        <!-- Header -->
        <div class="row g-3 align-items-center mb-4">
            <div class="col-md-8">
                <h3 class="text-primary mb-1">
                    <i class="fas fa-receipt me-2"></i>Detail Pembayaran Tahunan
                </h3>
                <p class="text-muted mb-0">
                    <strong>Gang:</strong> {{ $gangName }} |
                    <strong>Tahun:</strong> {{ $tahun }}
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('manage-rt.shared.laporan.tahunan-gang') }}"
                    class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        @if ($pembayarans->isEmpty())
            <!-- Tidak ada data -->
            <div class="text-center py-5">
                <i class="fas fa-receipt text-muted" style="font-size: 4rem; opacity: 0.2;"></i>
                <h5 class="text-muted mt-3">Tidak ada pembayaran ditemukan</h5>
                <p class="text-muted">Belum ada transaksi pembayaran untuk gang ini di tahun {{ $tahun }}.</p>
            </div>
        @else
            <!-- Ringkasan Statistik -->
            @php
                $totalPembayaran = $pembayarans->sum('total_amount');
                $jumlahTransaksi = $pembayarans->count();
                $sudahSetor = $pembayarans->where('setoran_id', '!=', null)->count();
                $belumSetor = $jumlahTransaksi - $sudahSetor;
            @endphp

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-start border-primary border-4 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Total Transaksi</h6>
                            <h3 class="text-primary mb-0">{{ $jumlahTransaksi }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-start border-success border-4 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Total Pembayaran</h6>
                            <h3 class="text-success mb-0">
                                Rp {{ number_format($totalPembayaran, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-start border-warning border-4 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Belum Disetor</h6>
                            <h3 class="text-warning mb-0">{{ $belumSetor }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Utama -->
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 18%;">Tanggal & Waktu</th>
                                <th style="width: 15%;">Rumah</th>
                                <th style="width: 12%;">Metode</th>
                                <th style="width: 20%;">Jumlah</th>
                                <th style="width: 15%;">Status Setoran</th>
                                <th class="text-center" style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembayarans as $pembayaran)
                                <tr>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($pembayaran->created_at)->format('d M Y') }}</strong>
                                        <br>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($pembayaran->created_at)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded">{{ $pembayaran->house_id }}</code>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $pembayaran->payment_method == 'cash' ? 'success' : 'info' }} px-2 py-1">
                                            <i
                                                class="fas fa-{{ $pembayaran->payment_method == 'cash' ? 'money-bill-wave' : 'credit-card' }} me-1"></i>
                                            {{ ucfirst($pembayaran->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        Rp {{ number_format($pembayaran->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if ($pembayaran->setoran_id)
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
                                        <a href="{{ route('manage-rt.shared.detail-pembayaran.show', $pembayaran->id) }}"
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

                <!-- Footer -->
                <div class="card-footer bg-light py-2">
                    <small class="text-muted">
                        Menampilkan <strong>{{ $jumlahTransaksi }}</strong> transaksi dari gang
                        <strong>{{ $gangName }}</strong> di tahun <strong>{{ $tahun }}</strong>.
                    </small>
                </div>
            </div>
        @endif
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

        .badge.bg-danger-subtle {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Hover scale effect */
        .hover-scale:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        /* Table hover */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Code style */
        code {
            font-size: 0.9em;
            padding: 0.2em 0.4em;
            background-color: #f0f0f0;
            border-radius: 4px;
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
        }
    </style>
@endpush

@push('js')
    <script>
        // Efek animasi saat halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.querySelector('.container');
            if (container) {
                container.classList.add('animate__animated', 'animate__fadeIn');
            }
        });
    </script>
@endpush
