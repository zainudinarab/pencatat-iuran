{{-- resources/views/confirm_setoran.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Konfirmasi Setoran</h2>

        {{-- Check if there are any pending setoran --}}
        @if ($setorans->isEmpty())
            <div class="alert alert-info">Tidak ada setoran yang perlu dikonfirmasi.</div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Setoran</th>
                        <th>Total Setoran</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @dd($setorans) --}}
                    @foreach ($setorans as $setoran)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($setoran->tanggal_setoran)->format('d M Y') }}</td>
                            <td>{{ number_format($setoran->total_amount, 2) }}</td>
                            <td>{{ $setoran->collector->name }}</td>
                            <td>
                                {{ $setoran->status }}

                            </td>
                            <td>
                                {{-- If status is 'pending', allow confirmation --}}
                                @if ($setoran->status == 'pending')
                                    {{-- @can('konfirmasi_setorans-create') --}}
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmModal" data-id="{{ $setoran->id }}"
                                        data-petugas="{{ $setoran->collector->name }}"
                                        data-amount="{{ $setoran->total_amount }}">Konfirmasi</button>
                                    {{-- @endcan --}}
                                @else
                                    <span class="text-muted">Konfirmasi sudah dilakukan</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <hr>
        <h1>Konfirmasi Setoran</h1>

        <!-- Tabel untuk Menampilkan Data Konfirmasi Setoran -->
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>No.</th>

                    <th>Jumlah Setoran</th>
                    <th>Petugas</th>
                    <th>Bendahara</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($KonfirmasiSetoranPetugas as $index => $konfirmasi)
                    <tr>
                        <td>{{ $konfirmasiSetorans->firstItem() + $index }}</td>
                        <td>Rp {{ number_format($konfirmasi->setoran->total_amount, 0, ',', '.') }}</td>
                        {{-- <td>{{ $konfirmasi->setoran->total_amount }}</td> <!-- Menampilkan Jumlah Setoran --> --}}
                        <td>{{ $konfirmasi->setoran->petugas->name ?? 'Tidak Diketahui' }}</td>
                        <!-- Menampilkan Nama Petugas -->
                        <td>{{ $konfirmasi->bendahara->name }}</td> <!-- Menampilkan Nama Bendahara -->

                        <td>
                            <span class="badge {{ $konfirmasi->status == 'confirmed' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($konfirmasi->status) }}
                            </span>
                        </td> <!-- Menampilkan Status -->
                        <td>{{ $konfirmasi->catatan }}</td> <!-- Menampilkan Catatan -->
                        <td>{{ $konfirmasi->created_at->format('d-m-Y H:i:s') }}</td> <!-- Menampilkan Tanggal -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paging untuk Tabel -->
        <div class="d-flex justify-content-center mt-4">
            {{ $KonfirmasiSetoranPetugas->links() }}
        </div>
    </div>

    {{-- Modal for confirmation --}}
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Setoran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="confirmSetoranForm" method="POST" action="">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="setoran_id" id="setoran_id">

                        <p>Petugas: <span id="petugas_name" style="font-weight: bold;"></span></p>
                        <p>Total Setoran: <span id="amount" style="font-weight: bold;"></span></p>

                        <div class="form-group">
                            <label for="catatan">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3">trimasih dana sudah di terima</textarea>
                        </div>

                        <div class="form-group">
                            <label>Status</label><br>
                            <label class="radio-inline"><input type="radio" name="status" value="confirmed" checked>
                                Diterima</label>
                            <label class="radio-inline"><input type="radio" name="status" value="ditolak">
                                Ditolak</label>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    </div>

@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var confirmModal = document.getElementById('confirmModal');
            // Ambil elemen modal dan form
            const modal = document.getElementById('confirmModal');
            const form = document.getElementById('confirmSetoranForm');

            confirmModal.addEventListener('show.bs.modal', function(event) {
                // Ambil data dari tombol yang diklik
                var button = event.relatedTarget; // Tombol yang memicu modal
                var setoranId = button.getAttribute('data-id');
                var petugas = button.getAttribute('data-petugas');
                var amount = button.getAttribute('data-amount');

                var rupiah = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
                // Set URL action form
                const actionUrl =
                    `/setoran/${setoranId}/konfirmasi`; // Sesuaikan dengan URL rute di aplikasi kamu
                form.setAttribute('action', actionUrl)
                // Isi data di dalam modal
                document.getElementById('setoran_id').value = setoranId; // Isi id setoran ke input hidden
                document.getElementById('petugas_name').textContent = petugas; // Isi nama petugas


                // document.getElementById('amount').textContent = amount; // Isi jumlah setoran
                document.getElementById('amount').textContent = rupiah;
            });
        });
    </script>
@endpush
