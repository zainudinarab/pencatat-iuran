<!-- resources/views/pengeluaran/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tambah Pengeluaran</h1>

        <form action="{{ route('pengeluaran.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="amount">Jumlah Pengeluaran</label>
                <input type="text" name="formatted_amount" id="formatted_amount" class="form-control" required>
                <input type="hidden" name="amount" id="amount" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <input type="text" name="description" id="description" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tanggal_pengeluaran">Tanggal Pengeluaran</label>
                <input type="date" name="tanggal_pengeluaran" id="tanggal_pengeluaran" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
@push('js')
    <script>
        const formattedAmountInput = document.getElementById('formatted_amount');
        const hiddenAmountInput = document.getElementById('amount');

        // Fungsi untuk format ke Rupiah
        function formatRupiah(angka, prefix = 'Rp ') {
            const number_string = angka.replace(/[^,\d]/g, '').toString();
            const split = number_string.split(',');
            let remainder = split[0].length % 3;
            let rupiah = split[0].substr(0, remainder);
            const thousands = split[0].substr(remainder).match(/\d{3}/gi);

            if (thousands) {
                const separator = remainder ? '.' : '';
                rupiah += separator + thousands.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
        }
        // Event listener saat mengetik
        formattedAmountInput.addEventListener('input', function(e) {
            // Hapus format Rupiah dan simpan nilai asli di hidden input
            let rawValue = formattedAmountInput.value.replace(/[^\d]/g, '');
            formattedAmountInput.value = formatRupiah(rawValue);
            hiddenAmountInput.value = rawValue; // Simpan nilai asli tanpa format
        });

        function getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tanggal_pengeluaran').value = getCurrentDate();
        });
    </script>
@endpush
