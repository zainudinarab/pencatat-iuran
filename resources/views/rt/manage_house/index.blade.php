@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Daftar Rumah</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('manage-rt.houses.create') }}">Tambah Rumah</a>
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
                <th>ID Rumah</th>
                <th>Blok</th>
                <th>Nomer</th>
                <th>RT</th>
                <th>Gang</th>
                <th>Pemilik rumah</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($houses as $index => $house)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $house->id }}</td>
                    <td>{{ $house->blok }}</td>
                    <td>{{ $house->nomer }}</td>
                    <td>{{ $house->rt->name }}</td>
                    <td>{{ $house->gang->name }}</td>
                    <td>{{ $house->name }}</td>
                    <td>{{ $house->address }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('manage-rt.houses.edit', $house->id) }}">Edit</a>

                        <form action="{{ route('manage-rt.houses.destroy', $house->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menghapus rumah ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {!! $houses->links('pagination::bootstrap-5') !!}

    <p class="text-center text-primary"><small>Bay: arnet</small></p>
@endsection
