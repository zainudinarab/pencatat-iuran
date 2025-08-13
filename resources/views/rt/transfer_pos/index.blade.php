@extends('layouts.rt')

@section('page-title', 'Daftar Transfer Pos RT')
@section('back-url', null)

@section('content')
    <div class="row mb-4">
        @foreach ($posList as $pos)
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-white">{{ ucfirst($pos->pos) }}</h5>
                        <p class="card-text fs-4">Rp {{ number_format($pos->saldo, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><i class="fas fa-exchange-alt me-2"></i> Transfer Dana Pos RT</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#transferModal">
            <i class="fas fa-plus-circle me-1"></i> Transfer Dana
        </button>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <strong>Transfer Masuk Menunggu Konfirmasi</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pengirim</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transferSiapTerima as $index => $transfer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $transfer->nama_pengirim ?? '-' }} <br>
                                    <small class="text-muted">({{ ucfirst($transfer->pengirim_pos) }})</small>
                                </td>

                                <td><span class="badge bg-success">Rp
                                        {{ number_format($transfer->jumlah, 0, ',', '.') }}</span></td>
                                <td>
                                    @if ($transfer->status == 'pending')
                                        <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                    @elseif ($transfer->status == 'disetujui')
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    @elseif ($transfer->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>{{ $transfer->created_at->format('d-m-Y H:i') }}</td>
                                <td class="text-center">
                                    @if ($transfer->status === 'pending' && $transfer->penerima_pos === $posSaldo->pos)
                                        <button type="button" class="btn btn-sm btn-success btn-konfirmasi-transfer"
                                            data-id="{{ $transfer->id }}"
                                            data-jumlah="{{ number_format($transfer->jumlah, 0, ',', '.') }}"
                                            data-action="{{ route('manage-rt.shared.transfer-pos.konfirmasi', $transfer->id) }}">
                                            <i class="fas fa-check-circle"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-danger btn-tolak-transfer"
                                            data-id="{{ $transfer->id }}"
                                            data-jumlah="{{ number_format($transfer->jumlah, 0, ',', '.') }}"
                                            data-action="{{ route('manage-rt.shared.transfer-pos.tolak', $transfer->id) }}">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox me-1"></i> Tidak ada transfer yang menunggu konfirmasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white">
            <strong>Riwayat Semua Transfer</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Confirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatTransfer as $index => $transfer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $transfer->nama_pengirim ?? '-' }} <br>
                                    <small class="text-muted">({{ ucfirst($transfer->pengirim_pos) }})</small>
                                </td>
                                <td>
                                    {{ $transfer->nama_penerima ?? '-' }} <br>
                                    <small class="text-muted">({{ ucfirst($transfer->penerima_pos) }})</small>
                                </td>
                                <td><span class="badge bg-success">Rp
                                        {{ number_format($transfer->jumlah, 0, ',', '.') }}</span></td>
                                <td>
                                    @if ($transfer->status == 'pending')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($transfer->status == 'disetujui')
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $transfer->created_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    @if ($transfer->confirmed_at)
                                        <small
                                            class="text-muted">{{ $transfer->confirmed_at->format('d-m-Y H:i') }}</small>
                                    @else
                                        <em class="text-muted">Belum dikonfirmasi</em>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox me-1"></i> Belum ada riwayat transfer.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal Transfer Dana -->
    <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('manage-rt.shared.transfer-pos.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transferModalLabel">Transfer Dana</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Pos Pengirim</label>
                            <select name="pengirim_pos" class="form-control" required>
                                @foreach ($posList as $pos)
                                    <option value="{{ $pos->pos }}">
                                        {{ ucfirst($pos->pos) }} (Saldo: Rp {{ number_format($pos->saldo, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="mb-3">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Dana Operasional Bulan Ini"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim Transfer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="modalKonfirmasiTransfer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formKonfirmasiTransfer">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" id="modalHeaderTransfer">
                        <h5 class="modal-title" id="modalTitleTransfer">Konfirmasi Transfer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p id="modalMessageTransfer">Apakah Anda yakin ingin melakukan aksi ini?</p>
                        <div><strong>Jumlah:</strong> <span id="jumlahDanaModal"></span></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn" id="submitModalBtn">Ya, Lanjutkan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasiTransfer'));
            const form = document.getElementById('formKonfirmasiTransfer');
            const jumlahSpan = document.getElementById('jumlahDanaModal');
            const modalTitle = document.getElementById('modalTitleTransfer');
            const modalMessage = document.getElementById('modalMessageTransfer');
            const modalHeader = document.getElementById('modalHeaderTransfer');
            const submitBtn = document.getElementById('submitModalBtn');

            // Utility untuk set warna modal
            function setModalStyle(type) {
                // Reset kelas dulu
                modalHeader.classList.remove('bg-success', 'bg-danger', 'text-white');
                submitBtn.classList.remove('btn-success', 'btn-danger');

                if (type === 'terima') {
                    modalHeader.classList.add('bg-success', 'text-white');
                    submitBtn.classList.add('btn-success');
                } else if (type === 'tolak') {
                    modalHeader.classList.add('bg-danger', 'text-white');
                    submitBtn.classList.add('btn-danger');
                }
            }

            // Handler tombol terima
            document.querySelectorAll('.btn-konfirmasi-transfer').forEach(button => {
                button.addEventListener('click', function() {
                    const jumlah = this.dataset.jumlah;
                    const action = this.dataset.action;

                    form.action = action;
                    jumlahSpan.textContent = 'Rp ' + jumlah;
                    modalTitle.textContent = 'Konfirmasi Transfer Dana';
                    modalMessage.textContent =
                        'Apakah Anda yakin ingin MENERIMA transfer dana ini?';

                    setModalStyle('terima');

                    modal.show();
                });
            });

            // Handler tombol tolak
            document.querySelectorAll('.btn-tolak-transfer').forEach(button => {
                button.addEventListener('click', function() {
                    const jumlah = this.dataset.jumlah;
                    const action = this.dataset.action;

                    form.action = action;
                    jumlahSpan.textContent = 'Rp ' + jumlah;
                    modalTitle.textContent = 'Tolak Transfer Dana';
                    modalMessage.textContent = 'Apakah Anda yakin ingin MENOLAK transfer dana ini?';

                    setModalStyle('tolak');

                    modal.show();
                });
            });
        });
    </script>
@endpush
