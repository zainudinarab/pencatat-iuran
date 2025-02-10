@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Laporan Penarikan Berdasarkan RT</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>RT</th>
                    <th>Total Amount</th>
                    <th>Total Setor</th>
                    <th>Total Belum Setor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($totals as $total)
                    <tr>
                        <td>{{ $total->RT }}</td>
                        <td>Rp {{ number_format($total->total_amount, 0) }}</td>
                        <td>Rp {{ number_format($total->total_setor, 0) }}</td>
                        <td>Rp {{ number_format($total->total_belum_setor, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
@push('js')
@endpush
