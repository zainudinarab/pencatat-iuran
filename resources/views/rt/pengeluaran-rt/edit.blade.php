@extends('layouts.rt')

@section('page-title', 'Edit Pengeluaran RT')
@section('back-url', url()->previous())

@push('css')
    <style>
        /* Kamu bisa reuse style dari create */
        .form-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* ... style lain sama seperti create */
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card form-card border-0">
                    <div class="card-header bg-primary text-white rounded-top">
                        <h5 class="mb-0">
                            <i class="bi bi-cash-coin me-2"></i>
                            Edit Pengeluaran RT
                        </h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('manage-rt.shared.pengeluaran.update', $pengeluaran->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="rt_id" value="{{ $rt->id }}">

                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-house me-1"></i> RT</label>
                                <input type="text" class="form-control" value="{{ $rt->name }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal" class="form-label"><i class="bi bi-calendar-event me-1"></i>
                                    Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control"
                                    value="{{ old('tanggal', $pengeluaran->tanggal->format('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_pencatat" class="form-label"><i class="bi bi-person me-1"></i> Nama
                                    Pencatat</label>
                                <input type="text" name="nama_pencatat" id="nama_pencatat" class="form-control"
                                    value="{{ old('nama_pencatat', $pengeluaran->nama_pencatat) }}" readonly
                                    style="background-color: #f8f9fa;">
                                @error('nama_pencatat')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="catatan" class="form-label"><i class="bi bi-journal-text me-1"></i> Catatan
                                    Umum</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3"
                                    placeholder="Catatan tambahan untuk pengeluaran ini...">{{ old('catatan', $pengeluaran->catatan) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="bukti_gambar" class="form-label"><i class="bi bi-image me-1"></i> Bukti
                                    Gambar</label>
                                <input type="file" name="bukti_gambar" id="bukti_gambar" class="form-control"
                                    accept="image/*">
                                @if ($pengeluaran->bukti_gambar)
                                    <small class="text-muted">Gambar saat ini:</small><br>
                                    <img src="{{ asset('storage/' . $pengeluaran->bukti_gambar) }}" alt="Bukti Gambar"
                                        style="max-width: 150px; margin-top: 8px;">
                                @endif
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                                @error('bukti_gambar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3 text-primary"><i class="bi bi-list-ul me-2"></i> Daftar Item Pengeluaran</h5>

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle" id="itemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th>Harga Satuan</th>
                                            <th>Catatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (old('items', $pengeluaran->items->toArray()) as $index => $item)
                                            <tr>
                                                <td>
                                                    <input type="text" name="items[{{ $index }}][nama_item]"
                                                        class="form-control"
                                                        value="{{ old("items.$index.nama_item", $item['nama_item']) }}"
                                                        placeholder="Contoh: Semen" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $index }}][jumlah]"
                                                        class="form-control" min="1"
                                                        value="{{ old("items.$index.jumlah", $item['jumlah']) }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="items[{{ $index }}][satuan]"
                                                        class="form-control"
                                                        value="{{ old("items.$index.satuan", $item['satuan'] ?? '') }}"
                                                        placeholder="Contoh: sak">
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" step="0.01"
                                                            name="items[{{ $index }}][harga_satuan]"
                                                            class="form-control"
                                                            value="{{ old("items.$index.harga_satuan", $item['harga_satuan']) }}"
                                                            placeholder="0.00" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="items[{{ $index }}][catatan]"
                                                        class="form-control"
                                                        value="{{ old("items.$index.catatan", $item['catatan'] ?? '') }}"
                                                        placeholder="Opsional">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-sm remove-item"><i
                                                            class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-add btn-sm mb-3" id="addItem">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Item
                            </button>

                            <!-- Total Pengeluaran -->
                            <div class="mb-4 text-end">
                                <h5>Total Pengeluaran: <span id="totalPengeluaranDisplay" class="text-primary">Rp 0</span>
                                </h5>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left-circle"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-save me-1"></i> Update Pengeluaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let itemIndex = {{ count(old('items', $pengeluaran->items)) }};

        document.getElementById('addItem').addEventListener('click', function() {
            const tableBody = document.querySelector('#itemsTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>
                <input type="text" name="items[${itemIndex}][nama_item]" class="form-control" placeholder="Nama item" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][jumlah]" class="form-control" min="1" value="1" required>
            </td>
            <td>
                <input type="text" name="items[${itemIndex}][satuan]" class="form-control" placeholder="Satuan">
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="0.01" name="items[${itemIndex}][harga_satuan]" class="form-control" placeholder="0.00" required>
                </div>
            </td>
            <td>
                <input type="text" name="items[${itemIndex}][catatan]" class="form-control" placeholder="Catatan">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
            tableBody.appendChild(row);
            itemIndex++;
            hitungTotalPengeluaran();
        });

        // Hapus baris
        document.querySelector('#itemsTable').addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const row = e.target.closest('tr');
                if (document.querySelectorAll('#itemsTable tbody tr').length > 1) {
                    row.remove();
                    hitungTotalPengeluaran();
                } else {
                    alert('Minimal satu item harus ada.');
                }
            }
        });

        // Fungsi hitung total sama seperti di create
        function hitungTotalPengeluaran() {
            let total = 0;
            document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
                const jumlahInput = row.querySelector('input[name*="[jumlah]"]');
                const hargaInput = row.querySelector('input[name*="[harga_satuan]"]');
                const jumlah = parseFloat(jumlahInput?.value || 0);
                const harga = parseFloat(hargaInput?.value || 0);
                total += jumlah * harga;
            });
            document.getElementById('totalPengeluaranDisplay').textContent = formatRupiah(total);
        }

        function formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        document.addEventListener('input', function(e) {
            if (e.target.name?.includes('[jumlah]') || e.target.name?.includes('[harga_satuan]')) {
                hitungTotalPengeluaran();
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            hitungTotalPengeluaran();
        });
    </script>
@endpush
