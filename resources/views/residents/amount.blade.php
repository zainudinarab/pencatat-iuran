@extends('layouts.app')
@push('css')
@endpush

@section('content')
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Blok</th>
                    <th>Nama</th>
                    <th>Tarjet Pembayaran</th>
                    <th>Total Penarikan</th>
                    <th>Acsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($residents as $resident)
                    <tr class="{{ $totalAmount > $resident->penarikan_sum_amount ? 'table-danger' : 'table-success' }}">
                        <td>{{ $resident->slug }}</td>
                        <td>{{ $resident->name }}</td>
                        <td>
                            {{ number_format($totalAmount, 0, ',', '.') }}
                        </td>
                        <td>
                            {{ number_format($resident->penarikan_sum_amount, 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('residents.detail', $resident->id) }}" class="btn btn-primary">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
@endsection
