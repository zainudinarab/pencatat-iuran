{{-- resources/views/dashboard/rt.blade.php --}}
@extends('layouts.rt')

@section('title', 'Dashboard RT')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Saldo Utama RT -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Saldo Keseluruhan RT</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="text-success fw-bold">
                            Rp {{ number_format($saldoRt->total_saldo ?? 0, 2, ',', '.') }}
                        </h2>
                        <p class="text-muted">Per {{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Saldo Posisi (Bendahara / Ketua) -->
            @if ($saldoPos)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Saldo Anda sebagai {{ ucfirst(str_replace('_', ' ', $saldoPos->pos)) }}</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-primary fw-bold">
                                Rp {{ number_format($saldoPos->saldo, 2, ',', '.') }}
                            </h2>
                            <p class="text-muted">Dikelola oleh {{ auth()->user()->name }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Riwayat Transaksi -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Riwayat Transaksi</h5>
                        <span class="badge bg-light text-dark">
                            RT {{ $rt->name ?? 'Tidak Diketahui' }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        @if ($transaksi->isEmpty())
                            <div class="text-center py-5">
                                <p class="text-muted">Belum ada transaksi.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th>Sumber</th>
                                            <th>Nominal</th>
                                            <th>Saldo Setelah</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksi as $t)
                                            <tr>
                                                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span
                                                        class="badge
                                                    @if ($t->jenis == 'pemasukan') bg-success
                                                    @else bg-danger @endif">
                                                        {{ ucfirst($t->jenis) }}
                                                    </span>
                                                </td>
                                                <td>{{ $t->sumber ? ucfirst(str_replace('_', ' ', $t->sumber)) : '-' }}</td>
                                                <td
                                                    class="fw-bold text-{{ $t->jenis == 'pemasukan' ? 'success' : 'danger' }}">
                                                    {{ $t->jenis == 'pemasukan' ? '+' : '-' }}
                                                    Rp{{ number_format($t->nominal, 2, ',', '.') }}
                                                </td>
                                                <td>Rp{{ number_format($t->saldo_setelah ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ Str::limit($t->keterangan, 50) }}</td>
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
