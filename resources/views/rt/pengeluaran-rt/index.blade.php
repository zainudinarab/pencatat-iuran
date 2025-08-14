@extends('layouts.rt')


@section('page-title', 'Daftar Pengeluaran RT')
@section('back-url', url()->previous())
@section('content')
    <div class="container">


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <a href="{{ route('manage-rt.shared.pengeluaran.create') }}" class="btn btn-primary mb-3">+ Tambah Pengeluaran</a>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nomor Nota</th>
                        <th>RT</th>
                        <th>Pencatat</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Konfirmasi Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluarans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->nomor_nota ?? '-' }}</td>
                            <td>{{ $p->rt->name ?? '-' }}</td>
                            <td>{{ $p->nama_pencatat }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td>
                                @if ($p->status_konfirmasi === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($p->status_konfirmasi === 'confirmed')
                                    <span class="badge bg-success">Confirmed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($p->status_konfirmasi) }}</span>
                                @endif
                            </td>
                            <td>{{ $p->confirmedByUser->name ?? '-' }}</td>
                            <td>
                                {{-- Tombol Edit dan Hapus, hanya jika BELUM dikonfirmasi --}}
                                @if ($p->status_konfirmasi === 'pending')
                                    <a href="{{ route('manage-rt.shared.pengeluaran.edit', $p->id) }}"
                                        class="btn btn-sm btn-info">Edit</a>

                                    <form action="{{ route('manage-rt.shared.pengeluaran.destroy', $p->id) }}"
                                        method="POST" style="display:inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                @endif

                                {{-- Tombol Konfirmasi, hanya tampil jika:
        1. status masih pending
        2. user BUKAN pencatat
    --}}
                                @if ($p->status_konfirmasi === 'pending' && auth()->id() !== $p->user_id)
                                    <form action="{{ route('manage-rt.shared.pengeluaran.approve', $p->id) }}"
                                        method="POST" style="display:inline-block"
                                        onsubmit="return confirm('Konfirmasi pengeluaran ini?')">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Konfirmasi</button>
                                    </form>
                                @endif

                                {{-- Tombol Nota selalu muncul --}}
                                <a href="{{ route('manage-rt.shared.pengeluaran.nota', $p->id) }}"
                                    class="btn btn-sm btn-secondary">Nota</a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pengeluarans->links() }}
    </div>
@endsection
