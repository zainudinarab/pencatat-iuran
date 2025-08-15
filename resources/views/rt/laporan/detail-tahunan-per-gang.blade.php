@extends('layouts.rt')

@section('content')
    <div class="container">
        <h4 class="mb-4">Detail Pembayaran Tahun {{ $tahun }} - Gang {{ $gangName }}</h4>

        <a href="{{ route('manage-rt.shared.laporan.tahunan-gang') }}" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

        @if ($pembayarans->count())
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Rumah</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status Setoran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembayarans as $pembayaran)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($pembayaran->created_at)->format('d M Y H:i') }}</td>
                            <td>{{ $pembayaran->house_id }}</td>
                            <td>{{ ucfirst($pembayaran->payment_method) }}</td>
                            <td>Rp {{ number_format($pembayaran->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if ($pembayaran->setoran_id)
                                    <span class="badge bg-success">Sudah</span>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">Tidak ada pembayaran untuk tahun ini.</p>
        @endif
    </div>
@endsection
