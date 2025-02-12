<!-- resources/views/pengeluaran/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">

        <h3 class="mt-4">Daftar Pembayaran</h3>

        <table id="penarikans-table" class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Petugas</th>
                    <th>Resident</th>
                    <th>Jumlah</th>
                    <th>Penarikan</th>

                </tr>
            </thead>
            <tbody>
                {{-- @dd($penarikans) --}}
                @foreach ($penarikans as $index => $penarikan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $penarikan->petugas->name }}</td>
                        <td>{{ $penarikan->resident->name }}</td>
                        <td>{{ $penarikan->amount }}</td>
                        <td>{{ \Carbon\Carbon::parse($penarikan->tanggal_penarikan)->format('d M Y') }}

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('js')
@endpush
