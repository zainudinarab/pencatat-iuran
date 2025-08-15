@extends('layouts.rt')

@section('content')
    <div class="container">
        <h4 class="mb-3">Detail Pembayaran - {{ $petugas->name }}</h4>

        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

        @forelse ($pembayarans as $tanggal => $items)
            <h5 class="mt-4">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</h5>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Waktu Input</th>
                        <th>RT</th>
                        <th>Rumah</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status Setoran</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $bayar)
                        <tr>
                            <td>{{ $bayar->created_at->format('H:i:s') }}</td>
                            <td>{{ $bayar->rt->name ?? '-' }}</td>
                            <td>{{ $bayar->house_id }}</td>
                            <td>{{ number_format($bayar->total_amount, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($bayar->payment_method) }}</td>
                            <td>
                                @if ($bayar->setoran_id)
                                    <span class="badge bg-success">Sudah</span>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('manage-rt.shared.detail-pembayaran.show', $bayar->id) }}"
                                    class="btn btn-info btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @empty
            <p>Tidak ada data pembayaran.</p>
        @endforelse
    </div>
@endsection
