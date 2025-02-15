@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Daftar Pengeluaran RT</h2>
        <a href="{{ route('manage-rt.pengeluaran-rt.create') }}" class="btn btn-primary mb-3">Tambah Pengeluaran</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>RT</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Bendahara yang Menyetujui</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengeluaran as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->rt->name }}</td>
                        <td>{{ number_format($item->amount, 2) }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->approvedBy->name }}</td>
                        <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('manage-rt.pengeluaran.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('manage-rt.pengeluaran.destroy', $item->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {!! $pengeluaran->links('pagination::bootstrap-5') !!}
    </div>
@endsection
