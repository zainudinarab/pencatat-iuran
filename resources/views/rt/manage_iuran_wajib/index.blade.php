@extends('layouts.rt')

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Daftar Iuran Wajib</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('manage-rt.iuran-wajib.create') }}">Tambah Iuran Wajib</a>
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
                <th>RT</th>
                <th>Bulan Tagihan</th>
                <th>Nama Iuran</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($iuranWajib as $index => $iuran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $iuran->rt->name }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Ym', $iuran->bill_month)->format('F Y') }}</td>
                    <td>{{ $iuran->jenisIuran->name }}</td>
                    <td>{{ number_format($iuran->amount, 2) }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('manage-rt.iuran-wajib.edit', $iuran->id) }}">Edit</a>
                        <form action="{{ route('manage-rt.iuran-wajib.destroy', $iuran->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menghapus Iuran Wajib ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {!! $iuranWajib->links('pagination::bootstrap-5') !!}

    <p class="text-center text-primary"><small>Bay: arnet</small></p>
@endsection
