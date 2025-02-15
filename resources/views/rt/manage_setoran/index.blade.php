@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Daftar Setoran Petugas</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('manage-rt.setoran.create') }}">Tambah Setoran</a>
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
                <th>Petugas Penyetor</th>
                <th>RT Tujuan</th>
                <th>Total Setoran</th>
                <th>Status</th>
                <th>Bendahara yang Menyetujui</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($setoran as $index => $setoranItem)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $setoranItem->collector->name }}</td>
                    <td>{{ $setoranItem->rt->name }}</td>
                    <td>{{ number_format($setoranItem->total_amount, 2) }}</td>
                    <td>{{ ucfirst($setoranItem->status) }}</td>
                    <td>{{ $setoranItem->approvedBy ? $setoranItem->approvedBy->name : 'N/A' }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('manage-rt.setoran.edit', $setoranItem->id) }}">Edit</a>

                        <form action="{{ route('manage-rt.setoran.destroy', $setoranItem->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menghapus setoran ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {!! $setoran->links('pagination::bootstrap-5') !!}
@endsection
