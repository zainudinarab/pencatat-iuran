@extends('layouts.rt')

@push('css')
    <style>
        .checkbox-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .checkbox-item {
            padding: 8px 12px;
            margin-bottom: 6px;
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 6px;
            display: flex;
            align-items: center;
            transition: background-color 0.2s;
        }

        .checkbox-item:hover {
            background-color: #f0f8ff;
        }

        .checkbox-item input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .alert {
            border-radius: 8px;
        }

        /* Card menyesuaikan lebar parent */
        .form-card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .form-card .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.1rem;
            padding: 12px 20px;
        }

        .form-card .card-body {
            padding: 1.5rem;
        }
    </style>
@endpush

@section('page-title', 'Daftar RT')
@section('back-url', url()->previous())
@section('content')

    <!-- Card tanpa container, lebar mengikuti kontainer di layouts.rt -->
    <div class="card form-card">
        <div class="card-header">
            <i class="fas fa-edit me-2"></i>Update Setoran RT
        </div>

        <div class="card-body">
            <!-- Alert Error -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('manage-rt.setoran-petugas.update', $setoran->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="collector_id" value="{{ $setoran->collector_id }}">
                <input type="hidden" name="rt_id" value="{{ $setoran->rt_id }}">

                <!-- Tanggal Setoran -->
                <div class="mb-4">
                    <label for="tanggal_setoran" class="form-label">
                        <i class="fas fa-calendar-alt text-primary me-1"></i>
                        Tanggal Setoran
                    </label>
                    <input type="date" id="tanggal_setoran" name="tanggal_setoran" class="form-control form-control-lg"
                        value="{{ old('tanggal_setoran', \Carbon\Carbon::parse($setoran->tanggal_setoran)->format('Y-m-d')) }}"
                        required>
                </div>

                <!-- Pilih Penarikan -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-hand-holding-usd text-success me-1"></i>
                        Pilih Penarikan yang Disetorkan
                    </label>
                    <div class="checkbox-list">
                        @forelse ($pembayarans as $pembayaran)
                            <div class="checkbox-item">
                                <input type="checkbox" name="pembayaran_ids[]" value="{{ $pembayaran->id }}"
                                    data-amount="{{ $pembayaran->total_amount }}"
                                    {{ in_array($pembayaran->id, $selectedPembayaranIds) ? 'checked' : '' }}>
                                <div class="ms-2">
                                    <strong>{{ $pembayaran->house_id }}</strong> -
                                    <small class="text-muted">{{ $pembayaran->collector->name }}</small> -
                                    <span class="text-success fw-bold">
                                        Rp {{ number_format($pembayaran->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-box-open"></i> Tidak ada data penarikan tersedia.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Total Setoran -->
                <div class="mb-4">
                    <div class="alert alert-info text-center border-info" id="total_setoran" style="font-size: 1.1rem;">
                        <!-- Diisi oleh JavaScript -->
                    </div>
                    <input type="hidden" name="total_amount" id="total_amount" value="{{ $setoran->total_amount }}">
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('manage-rt.setoran-petugas.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('.checkbox-item input[type="checkbox"]');
            const totalAmountInput = document.getElementById('total_amount');

            function updateTotalSetoran() {
                let totalSetoran = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        totalSetoran += parseFloat(checkbox.dataset.amount);
                    }
                });

                const formattedTotal = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(totalSetoran);

                document.getElementById('total_setoran').textContent = `Total Setoran: ${formattedTotal}`;
                totalAmountInput.value = Math.floor(totalSetoran);
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotalSetoran);
            });

            updateTotalSetoran(); // Hitung awal
        });
    </script>

    <!-- Font Awesome (opsional, jika belum ada di layout) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush
