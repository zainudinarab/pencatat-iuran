@extends('layouts.rt')

@section('content')
    <div class="container">
        <h4 class="mb-4">Laporan Pembayaran Tahunan per Gang</h4>

        @forelse ($data as $tahun => $items)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>Tahun: {{ $tahun }}</strong>
                    <span class="float-end">Total: Rp {{ number_format($totalPerTahun[$tahun], 0, ',', '.') }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Gang</th>
                                <th>Total Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items as $row)
                                <tr>
                                    <td>{{ $gangs[$row->gang_id] ?? 'Tidak diketahui' }}</td>
                                    <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('manage-rt.shared.laporan.tahunan-gang.detail', ['tahun' => $tahun, 'gang_id' => $row->gang_id]) }}"
                                            class="btn btn-sm btn-info">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada data pembayaran yang ditemukan.</p>
        @endforelse
    </div>
@endsection
