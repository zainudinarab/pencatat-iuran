@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Laporan Penarikan Berdasarkan Petugas</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Petugas</th>
                    <th>Total Tarikan</th>
                    <th>Total Setor</th>
                    <th>Total Belum Setor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($totals as $row)
                    <tr>
                        <td>{{ $row->petugas->name }}</td>
                        <td>{{ number_format($row->total_amount, 0) }}</td>
                        <td>{{ number_format($row->total_setor, 0) }}</td>
                        <td>{{ number_format($row->total_belum_setor, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
@push('js')
@endpush
