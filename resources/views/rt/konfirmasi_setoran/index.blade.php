@extends('layouts.rt')

@section('page-title', 'Konfirmasi Setoran')
@section('content')

    <div class="container-fluid py-4">

        {{-- Pending --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Setoran Pending</h5>
            </div>
            <div class="card-body">
                @if ($pendingSetoran->isEmpty())
                    <p class="text-muted text-center m-0">Tidak ada setoran pending.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Petugas</th>
                                    <th>RT</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingSetoran as $setoran)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $setoran->collector->name }}</td>
                                        <td>{{ $setoran->rt->name }}</td>
                                        <td>Rp {{ number_format($setoran->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @can('confirm', $setoran)
                                                <button class="btn btn-success btn-sm open-confirm-modal"
                                                    data-id="{{ $setoran->id }}" data-name="{{ $setoran->collector->name }}">
                                                    Konfirmasi
                                                </button>
                                            @endcan

                                            @can('reject', $setoran)
                                                <button class="btn btn-danger btn-sm open-reject-modal"
                                                    data-id="{{ $setoran->id }}" data-name="{{ $setoran->collector->name }}">
                                                    Tolak
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $pendingSetoran->appends([
                                'confirmed_page' => request('confirmed_page'),
                                'rejected_page' => request('rejected_page'),
                            ])->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Confirmed --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Setoran Sudah Dikonfirmasi</h5>
            </div>
            <div class="card-body">
                @if ($confirmedSetoran->isEmpty())
                    <p class="text-muted text-center m-0">Belum ada setoran dikonfirmasi.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Petugas</th>
                                    <th>RT</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($confirmedSetoran as $setoran)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $setoran->collector->name }}</td>
                                        <td>{{ $setoran->rt->name }}</td>
                                        <td>Rp {{ number_format($setoran->total_amount, 0, ',', '.') }}</td>
                                        <td><span class="badge bg-success">Confirmed</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $confirmedSetoran->appends([
                                'pending_page' => request('pending_page'),
                                'rejected_page' => request('rejected_page'),
                            ])->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Rejected --}}
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Setoran Ditolak</h5>
            </div>
            <div class="card-body">
                @if ($rejectedSetoran->isEmpty())
                    <p class="text-muted text-center m-0">Belum ada setoran ditolak.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Petugas</th>
                                    <th>RT</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rejectedSetoran as $setoran)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $setoran->collector->name }}</td>
                                        <td>{{ $setoran->rt->name }}</td>
                                        <td>Rp {{ number_format($setoran->total_amount, 0, ',', '.') }}</td>
                                        <td><span class="badge bg-danger">Ditolak</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $rejectedSetoran->appends([
                                'pending_page' => request('pending_page'),
                                'confirmed_page' => request('confirmed_page'),
                            ])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="confirmForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Setoran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Terima setoran dari <strong id="confirmName"></strong>?</p>
                        <div class="mb-3">
                            <label>Catatan (opsional):</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Setoran diterima dengan baik.">Setoran diterima dengan baik</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya, Terima</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Tolak Setoran?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda yakin ingin menolak setoran dari <strong id="rejectName"></strong>?</p>
                        <div class="mb-3">
                            <label>Harap beri alasan:</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Alasan penolakan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        document.querySelectorAll('.open-confirm-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;
                let name = this.dataset.name;
                document.getElementById('confirmName').textContent = name;

                document.getElementById('confirmForm').action = `/manage-rt/konfirmasi-setoran/${id}`;
                new bootstrap.Modal(document.getElementById('confirmModal')).show();
            });
        });

        document.querySelectorAll('.open-reject-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;
                let name = this.dataset.name;
                document.getElementById('rejectName').textContent = name;
                document.getElementById('rejectForm').action = `/manage-rt/batalkan-konfirmasi/${id}`;
                new bootstrap.Modal(document.getElementById('rejectModal')).show();
            });
        });
    </script>
@endpush
