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
                            <a href="{{ route('residents.show', $resident->id) }}" class="btn btn-primary">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-danger">
                    <td>1</td>
                    <td>John Doe</td>
                    <td>100.000</td>
                </tr>
                <tr class="bg-success text-white">
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>150.000</td>
                </tr>
                <tr class="bg-warning text-dark">
                    <td>3</td>
                    <td><span class="badge bg-success ">

                        </span></td>
                    <td>120.000</td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection
