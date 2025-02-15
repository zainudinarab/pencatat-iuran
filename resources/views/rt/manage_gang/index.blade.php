@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Daftar Gang</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('manage-rt.gangs.create') }}">Tambah Gang</a>
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
                <th>Nama Gang</th>
                <th>RT</th>
                <th>Ketua Gang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gangs as $index => $gang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $gang->name }}</td>
                    <td>{{ $gang->rt->name }}</td>
                    <td>{{ $gang->ketuaGang->name }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('manage-rt.gangs.edit', $gang->id) }}">Edit</a>

                        <form action="{{ route('manage-rt.gangs.destroy', $gang->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menghapus Gang ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {!! $gangs->links('pagination::bootstrap-5') !!}

    <p class="text-center text-primary"><small>Bay: arnet</small></p>
@endsection
