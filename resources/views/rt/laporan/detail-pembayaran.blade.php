@extends('layouts.rt')

@section('content')
    <div class="container">
        <h4 class="mb-3">Detail Rincian Pembayaran - ID: {{ $pembayaran->id }}</h4>
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Iuran</th>
                    <th>Rumah</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran->detailPembayarans as $detail)
                    <tr>
                        <td>{{ $detail->iuranWajib->jenisIuran->name ?? '-' }}</td> {{-- Nama jenis iuran --}}
                        <td>{{ $detail->house_id }}</td>
                        <td>{{ number_format($detail->amount, 0, ',', '.') }}</td>
                        <td>
                            @if ($detail->status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
