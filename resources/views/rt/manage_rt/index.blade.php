@extends('layouts.rt')

@section('page-title', 'Daftar RT')
@section('back-url', url()->previous())

@section('content')
    {{-- Header Button --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end">
            <a class="btn btn-success" href="{{ route('manage-rt.rts.create') }}">
                <i class="fas fa-plus me-1"></i> Tambah RT
            </a>
        </div>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Data RT</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama RT</th>
                            <th>Ketua RT</th>
                            <th>Bendahara</th>
                            <th>RW</th>
                            <th>Desa/Kelurahan</th>
                            <th>Kecamatan</th>
                            <th>Kota/Kabupaten</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rts as $index => $rt)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $rt->name }}</td>
                                <td>{{ $rt->ketuaRt->name }}</td>
                                <td>{{ $rt->bendahara->name }}</td>
                                <td>{{ $rt->rw }}</td>
                                <td>{{ $rt->village }}</td>
                                <td>{{ $rt->district }}</td>
                                <td>{{ $rt->city }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-primary me-1"
                                        href="{{ route('manage-rt.rts.edit', $rt->id) }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('manage-rt.rts.destroy', $rt->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus RT ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada data RT</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {!! $rts->links('pagination::bootstrap-5') !!}
    </div>

    <p class="text-center text-muted mt-4"><small>Bay: arnet</small></p>
@endsection
