@extends('layouts.app')
@push('css')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .checkbox-list {
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .checkbox-item {
            margin-bottom: 5px;
        }

        .total-setoran {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
@endpush


@section('content')
    <div class="container">
        <h2>Tambah Setoran</h2>

        <form action="{{ route('setoran.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="petugas_id" class="form-label">ID Petugas:</label>
                <input type="number" name="petugas_id" class="form-control" value="{{ $petugas->id }}" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_setoran" class="form-label">Tanggal Setoran:</label>
                <input type="date" id="tanggal_setoran" name="tanggal_setoran" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Pilih Penarikan yang Belum Disetorkan:</label>
                <div class="checkbox-list" id="penarikan_list">
                    <!-- Data penarikan yang belum disetorkan akan dimuat di sini -->
                    @foreach ($penarikan as $penarikanItem)
                        @if ($penarikanItem->setoran_id === null)
                            <div class="checkbox-item">
                                <input type="checkbox" name="penarikan_ids[]" value="{{ $penarikanItem->id }}"
                                    data-amount="{{ $penarikanItem->amount }}">
                                {{ $penarikanItem->resident->slug }}- {{ $penarikanItem->resident->name }} - Rp
                                {{ number_format($penarikanItem->amount, 2) }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="mb-3">
                <div id="total_setoran"></div>
                <input type="text" name="total_amount" id="total_amount" value="">
            </div>
            <!-- Hidden input to store the selected penarikan IDs -->
            {{-- <input type="text" name="penarikan_ids_hidden" id="penarikan_ids_hidden" value=""> --}}

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('setoran.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
@push('js')
    <script>
        function getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        document.addEventListener("DOMContentLoaded", function() {
            // Select all the checkboxes
            const checkboxes = document.querySelectorAll('.checkbox-item input[type="checkbox"]');
            const hiddenInput = document.getElementById('penarikan_ids_hidden');
            const totalAmountInput = document.getElementById('total_amount');
            document.getElementById('tanggal_setoran').value = getCurrentDate();
            // Function to update the total setoran
            // Function to update the total setoran
            function updateTotalSetoran() {
                let totalSetoran = 0;
                let selectedIds = [];

                // Iterate over all the checkboxes
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        // Get the amount from the 'data-amount' attribute
                        const amount = parseFloat(checkbox.getAttribute('data-amount'));
                        totalSetoran += amount;
                        // Add the selected penarikan ID to the array
                        selectedIds.push(checkbox.value);
                    }
                });

                // Update the total setoran display
                document.getElementById('total_setoran').textContent = 'Total Setoran:  ' + totalSetoran
                    .toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    });

                totalAmountInput.value = Math.floor(totalSetoran);
                // Update the hidden input with the selected penarikan IDs
                hiddenInput.value = selectedIds.join(',');
            }

            // Add event listeners to each checkbox to update the total when checked/unchecked
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotalSetoran);
            });

            // Initial call to update the total (in case any checkboxes are already checked)
            updateTotalSetoran();
        });
    </script>
@endpush
