@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Daftar Setoran</h2>
        @can('setorans-create')
        <a href="{{ route('setoran.create') }}" class="btn btn-primary mb-3">Tambah Setoran</a>
        @endcan

       

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Petugas</th>
                    <th>Total Jumlah</th>
                    <th>Tanggal Setoran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($setorans as $setoran)
                    <tr>
                        <td>{{ $setoran->petugas->name }}</td>
                        <td>Rp{{ number_format($setoran->total_amount, 0, ',', '.') }}</td>
                        <td>{{ $setoran->tanggal_setoran }}</td>
                        <td>
                            <span class="badge {{ $setoran->status == 'confirmed' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($setoran->status) }} (by:{{ $setoran->bendahara->name ?? '' }})
                            </span>

                        </td>
                        <td>
                            @if ($setoran->status == 'pending')
                                {{-- <a href="{{ route('setoran.edit', $setoran->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                                @can('setorans-delete')
                                <form action="{{ route('setoran.destroy', $setoran->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                                @endcan
                                
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
