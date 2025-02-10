<!-- resources/views/pengeluaran/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Daftar Pengeluaran</h1>
        <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary">Tambah Pengeluaran</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pengeluaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengeluarans as $pengeluaran)
                    <tr>
                        <td>{{ $pengeluaran->id }}</td>
                        <td>{{ $pengeluaran->description }}</td>
                        <td>Rp {{ number_format($pengeluaran->amount, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('d M Y') }}</td>
                        <td>
                            @if ($loop->last)
                                <!-- Menampilkan tombol hanya untuk pengeluaran terakhir -->
                                <a href="{{ route('pengeluaran.edit', $pengeluaran) }}" class="btn btn-warning">Edit</a>
                                <!-- Tombol Hapus dengan Modal Konfirmasi -->
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    data-url="{{ route('pengeluaran.destroy', $pengeluaran) }}"
                                    data-amount="{{ $pengeluaran->amount }}">Hapus</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus pengeluaran ini? <br>
                    <strong>Jumlah Pengeluaran: Rp <span id="amount-to-delete"></span></strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" action="" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Tombol Hapus yang diklik
            const url = button.getAttribute('data-url'); // Ambil URL dari tombol
            const amount = button.getAttribute('data-amount'); // Ambil jumlah pengeluaran dari tombol
            const form = document.getElementById('deleteForm');
            form.setAttribute('action', url); // Set form action dengan URL yang sesuai

            // Tampilkan jumlah pengeluaran pada modal
            document.getElementById('amount-to-delete').textContent = amount.toLocaleString();
        });
    </script>
@endpush
