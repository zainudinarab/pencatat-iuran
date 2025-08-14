{{-- resources/views/dashboard/rt.blade.php --}}
@extends('layouts.rt')

@section('title', 'Dashboard RT')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Total Sudah Disetor -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Total Sudah Disetorkan</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="text-success fw-bold">
                            Rp {{ number_format($totalSudahDisetor, 2, ',', '.') }}
                        </h2>
                        <p class="text-muted">Transaksi yang sudah disetorkan ke bendahara</p>
                    </div>
                </div>
            </div>

            <!-- Total Belum Disetor -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Total Belum Disetorkan</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="text-warning fw-bold">
                            Rp {{ number_format($totalBelumDisetor, 2, ',', '.') }}
                        </h2>
                        <p class="text-muted">Transaksi yang belum disetorkan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Transaksi -->
        <!-- Riwayat Pembayaran -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Riwayat Pembayaran</h5>
                        <span class="badge bg-light text-dark">
                            Oleh: {{ auth()->user()->name }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        @if ($riwayatPembayaran->isEmpty())
                            <div class="text-center py-5">
                                <p class="text-muted">Belum ada pembayaran.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Metode</th>
                                            <th>Sumber</th>
                                            <th>Nominal</th>
                                            <th>Status Setoran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayatPembayaran as $pembayaran)
                                            <tr>
                                                <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ ucfirst($pembayaran->payment_method) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ ucfirst($pembayaran->payment_source) }}
                                                </td>
                                                <td class="fw-bold text-success">
                                                    Rp{{ number_format($pembayaran->total_amount, 2, ',', '.') }}
                                                </td>
                                                <td>
                                                    @if ($pembayaran->setoran_id)
                                                        <span class="badge bg-success">Sudah Disetor</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Belum Disetor</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
