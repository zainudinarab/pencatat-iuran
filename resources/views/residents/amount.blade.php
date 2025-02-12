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
                        <td>{{ $resident->id }}</td>
                        <td>{{ $resident->name }}</td>
                        <td>
                            @if ($resident->penarikan_sum_amount !== 210000)
                                @if ($totalAmount > $resident->penarikan_sum_amount)
                                    {{ number_format($totalAmount, 0, ',', '.') }} <span
                                        class="badge bg-danger">-{{ number_format($totalAmount - $resident->penarikan_sum_amount, 0, ',', '.') }}</span>
                                @else
                                    {{ number_format($totalAmount, 0, ',', '.') }}
                                    <span
                                        class="badge bg-success">+{{ number_format($resident->penarikan_sum_amount - $totalAmount, 0, ',', '.') }}</span>
                                @endif
                            @endif


                        </td>
                        <td>

                            @if ($resident->penarikan_sum_amount === 210000)
                                {{ number_format($resident->penarikan_sum_amount, 0, ',', '.') }} <span
                                    class="badge bg-success">Lunas</span>
                            @else
                                {{ number_format($resident->penarikan_sum_amount, 0, ',', '.') }}
                            @endif
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
