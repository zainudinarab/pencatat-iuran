@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Daftar RT</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('manage-rt.rts.create') }}">Tambah RT</a>
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
                <th>Nama RT</th>
                <th>Ketua RT</th>
                <th>Bendahara</th>
                <th>RW</th>
                <th>Desa/Kelurahan</th>
                <th>Kecamatan</th>
                <th>Kota/Kabupaten</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rts as $index => $rt)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $rt->name }}</td>
                    <td>{{ $rt->ketuaRt->name }}</td>
                    <td>{{ $rt->bendahara->name }}</td>
                    <td>{{ $rt->rw }}</td>
                    <td>{{ $rt->village }}</td>
                    <td>{{ $rt->district }}</td>
                    <td>{{ $rt->city }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('manage-rt.rts.edit', $rt->id) }}">Edit</a>

                        <form action="{{ route('manage-rt.rts.destroy', $rt->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menghapus RT ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {!! $rts->links('pagination::bootstrap-5') !!}

    <p class="text-center text-primary"><small>Bay: arnet</small></p>
@endsection
