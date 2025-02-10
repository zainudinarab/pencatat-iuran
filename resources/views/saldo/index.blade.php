@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Saldo dan Log Saldo</h1>

        <!-- Menampilkan Saldo Terkini -->
        <div class="card">
            <div class="card-header">
                Saldo Terkini
            </div>
            <div class="card-body">
                <h5 class="card-title">Rp {{ number_format($saldo->saldo ?? 0, 0, ',', '.') }}</h5>
            </div>
        </div>

        <hr>

        <!-- Menampilkan Total Setoran dan Pengeluaran -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Total Setoran
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Rp {{ number_format($totalSetoran, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Total Pengeluaran
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Menampilkan Log Saldo -->
        <h3>Log Saldo</h3>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Transaksi</th>
                    <th>Jumlah</th>
                    <th>Saldo Terakhir</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logSaldos as $index => $logSaldo)
                    <tr>
                        {{-- nomer urut --}}
                        <td>{{ $logSaldos->firstItem() + $index }}</td>
                        <td>{{ ucfirst($logSaldo->jenis_transaksi) }}</td>
                        <td>Rp {{ number_format($logSaldo->jumlah, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($logSaldo->saldo_terakhir, 0, ',', '.') }}</td>
                        <td>{{ $logSaldo->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Paging -->
        <div class="d-flex justify-content-center mt-4">
            {{ $logSaldos->links() }}
        </div>
    </div>
@endsection
