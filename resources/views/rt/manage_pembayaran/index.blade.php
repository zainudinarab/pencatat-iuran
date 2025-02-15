@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Daftar Pembayaran</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('manage-rt.pembayaran.create') }}">Tambah Pembayaran</a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Rumah</th>
                <th>Jumlah</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
                <th>Petugas Penerima</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayaran as $index => $pembayaranItem)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pembayaranItem->house->address }}</td>
                    <td>{{ number_format($pembayaranItem->total_amount, 2) }}</td>
                    <td>{{ ucfirst($pembayaranItem->payment_method) }}</td>
                    <td>{{ ucfirst($pembayaranItem->status) }}</td>
                    <td>{{ $pembayaranItem->collector ? $pembayaranItem->collector->name : 'N/A' }}</td>
                    <td>
                        <a class="btn btn-primary"
                            href="{{ route('manage-rt.pembayaran.edit', $pembayaranItem->id) }}">Edit</a>

                        <form action="{{ route('manage-rt.pembayaran.destroy', $pembayaranItem->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menghapus pembayaran ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {!! $pembayaran->links('pagination::bootstrap-5') !!}
@endsection
