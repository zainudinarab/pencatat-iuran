@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Daftar Penarikan</h2>
        <a href="{{ route('penarikan.create') }}" class="btn btn-primary mb-3">Tambah Penarikan</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Petugas</th>
                    <th>Resident</th>
                    <th>Jumlah</th>
                    <th>Tanggal Penarikan</th>
                    <th>Setoran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penarikans as $penarikan)
                    <tr>
                        <td>{{ $penarikan->petugas->name }}</td>
                        <td>{{ $penarikan->resident->name }}</td>
                        <td>Rp{{ number_format($penarikan->amount, 2, ',', '.') }}</td>
                        <td>{{ $penarikan->tanggal_penarikan }}</td>
                        <td>{{ $penarikan->setoran ? $penarikan->setoran->id : '-' }}</td>
                        <td>
                            <a href="{{ route('penarikan.edit', $penarikan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('penarikan.destroy', $penarikan->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
