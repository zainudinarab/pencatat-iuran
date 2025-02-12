<!-- resources/views/pengeluaran/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">

        <h3 class="mt-4">Daftar Pembayaran</h3>

        <table id="penarikans-table" class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Petugas</th>
                    <th>Resident</th>
                    <th>Jumlah</th>
                    <th>Tanggal Penarikan</th>
                    <th>Setoran</th>
                </tr>
            </thead>
            <tbody id="penarikans-body">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>
@endsection
@push('js')
    <script>
        window.onload = function() {
            var residentId = @json($resident->id);
            console.log(residentId);
            renderFilteredPaymentTable(residentId);
        };

        function renderFilteredPaymentTable(resident_id) {
            // Pastikan resident_id ada, lalu panggil API dengan parameter resident_id
            if (resident_id) {
                fetch(`/penarikan-by-residents?resident_id=${resident_id}`)
                    .then(response => response.json()) // Mengubah respons menjadi JSON
                    .then(data => {
                        let tableBody = document.getElementById('penarikans-body');
                        tableBody.innerHTML = ''; // Kosongkan tabel sebelum diisi ulang

                        data.forEach((penarikan, index) => {
                            let row = document.createElement('tr');

                            // Buat kolom untuk nomor urut (serial number)
                            let nomorUrutCell = document.createElement('td');
                            nomorUrutCell.textContent = index + 1; // Menampilkan nomor urut
                            row.appendChild(nomorUrutCell);

                            let petugasCell = document.createElement('td');
                            petugasCell.textContent = penarikan.petugas
                                .name; // Asumsi 'petugas' adalah relasi dengan model User
                            row.appendChild(petugasCell);

                            let residentCell = document.createElement('td');
                            residentCell.textContent = penarikan.resident
                                .name; // Asumsi 'resident' adalah relasi dengan model Resident
                            row.appendChild(residentCell);

                            let amountCell = document.createElement('td');
                            amountCell.textContent = 'Rp ' + penarikan.amount
                                .toLocaleString(); // Format angka dengan Rupiah
                            row.appendChild(amountCell);

                            let tanggalCell = document.createElement('td');
                            tanggalCell.textContent = penarikan.tanggal_penarikan;
                            row.appendChild(tanggalCell);

                            let setoranCell = document.createElement('td');
                            setoranCell.textContent = penarikan.setoran ? penarikan.setoran.id :
                                '-'; // Menampilkan ID Setoran jika ada
                            row.appendChild(setoranCell);

                            // Tambahkan baris ke tabel
                            tableBody.appendChild(row);
                        });

                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
        }
    </script>
@endpush
