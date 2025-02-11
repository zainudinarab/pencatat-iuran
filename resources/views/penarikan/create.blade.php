@extends('layouts.app')

@push('css')
    <style>
        .suggestions {
            border: 1px solid #cccccc;
            border-top: none;
            max-height: 150px;
            overflow-y: auto;
            background-color: #ffffff;
            position: absolute;
            /* Pastikan tampil di atas elemen lain */
            width: 100%;
            z-index: 1000;
            /* Agar tampil di atas elemen lain */
        }

        .suggestions div {
            padding: 10px;
            cursor: pointer;
        }

        .suggestions div:hover {
            background-color: #f0f0f0;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h2 class="mb-4">Tambah Penarikan</h2>
        <!-- Menampilkan Pesan Error Validasi -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="payment-form" action="{{ route('penarikan.store') }}" method="POST">
            @csrf
            <!-- Form Grup: Cari Anggota -->
            <div class="mb-3">
                <label for="member-search" class="form-label">Cari Anggota</label>
                <input type="text" id="member-search" class="form-control" placeholder="Ketik Nama atau ID Anggota"
                    autocomplete="off">
                <div id="suggestions" class="suggestions mt-2" style="display: none;"></div>
            </div>
            {{-- <input type="text" name="user_id" value="{{ Auth::user()->id }}"> --}}
            <input type="text" name="petugas_id" value="{{ $petugas->id }}" hidden>
            <!-- Form Grup: Anggota Terpilih -->
            <div class="mb-3">
                <label for="selected-member" class="form-label">Anggota Terpilih</label>
                <input type="text" id="selected-member" class="form-control" readonly
                    placeholder="Anggota belum dipilih">
                <input type="text" name="resident_id" id="resident_id" class="form-control" hidden>
            </div>

            <!-- Form Grup: Jumlah Pembayaran -->
            <div class="mb-3">
                <label for="amount" class="form-label">Jumlah Pembayaran (Rp)</label>
                <input type="text" id="amount" class="form-control" placeholder="Masukkan Jumlah Pembayaran" required>
            </div>
            <input type="text" name="amount" id="amount_numeric" class="form-control" hidden>

            <!-- Form Grup: Tanggal Penarikan -->
            <div class="mb-3">
                <label for="tanggal_penarikan" class="form-label">Tanggal Penarikan</label>
                <input type="date" class="form-control" id="tanggal_penarikan" name="tanggal_penarikan" required>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
        </form>

        <!-- Tabel Daftar Pembayaran -->
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
            // Fokuskan input dengan ID 'amount' begitu halaman dimuat
            document.getElementById('member-search').focus();
        };

        const amountInput = document.getElementById('amount');
        const amountNumericInput = document.getElementById('amount_numeric');

        // Fungsi untuk memformat input menjadi format Rupiah
        function formatRupiah(value) {
            // Menghapus karakter non-angka sebelum memformat
            value = value.replace(/[^\d]/g, '');

            // Menambahkan titik sebagai pemisah ribuan
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Menambahkan "Rp" di depan
            return 'Rp ' + value;
        }

        // Event listener untuk memformat inputan setiap kali pengguna mengetik
        amountInput.addEventListener('input', function() {
            let inputValue = amountInput.value;
            inputValue = formatRupiah(inputValue);
            amountInput.value = inputValue;

            // Set nilai numerik di input hidden
            amountNumericInput.value = getNumericValue();
        });

        // Fungsi untuk menghapus simbol Rupiah dan mengambil nilai numeriknya
        function getNumericValue() {
            let numericValue = amountInput.value.replace(/[^\d]/g, ''); // Hapus simbol Rupiah
            return parseInt(numericValue, 10); // Mengonversi menjadi angka
        }
        document.getElementById('payment-form').addEventListener('submit', function(event) {
            // Set nilai numerik di input hidden jika belum ada
            if (!amountNumericInput.value) {
                amountNumericInput.value = getNumericValue(amountInput.value);
            }
        });
        // Menggunakan data `residents` yang sudah dikirim dari controller
        let payments = [{
                memberId: "1",
                memberName: "Andi Pratama",
                amount: 500000
            },

            {
                memberId: "1",
                memberName: "Andi Pratama",
                amount: 600000
            },
            {
                memberId: "2",
                memberName: "Andi Pratama",
                amount: 700000
            },
            {
                memberId: 2,
                memberName: "Budi Santoso",
                amount: 700000
            },
            {
                memberId: "A003",
                memberName: "Citra Dewi",
                amount: 400000
            },
            {
                memberId: "A004",
                memberName: "Dewi Lestari",
                amount: 600000
            },
            {
                memberId: "A005",
                memberName: "Eko Prasetyo",
                amount: 800000
            },
            {
                memberId: "A001",
                memberName: "Andi Pratama",
                amount: 200000
            }
        ];
        const residents = @json($residents); // Mengonversi data `residents` dari PHP ke JavaScript

        const searchInput = document.getElementById("member-search");
        const suggestionsBox = document.getElementById("suggestions");
        const selectedMemberInput = document.getElementById("selected-member");

        // Fungsi untuk menampilkan saran anggota
        searchInput.addEventListener("input", function() {
            const query = searchInput.value.toLowerCase();
            if (query.length === 0) {
                suggestionsBox.style.display = "none";
                return;
            }

            // Pastikan 'resident.id' diubah ke string sebelum menggunakan 'toLowerCase'
            const filteredResidents = residents.filter(resident =>
                String(resident.slug).toLowerCase().includes(query) ||
                resident.name.toLowerCase().includes(query)
            );

            if (filteredResidents.length > 0) {
                suggestionsBox.innerHTML = "";
                filteredResidents.forEach(resident => {
                    const suggestionItem = document.createElement("div");
                    suggestionItem.textContent = `${resident.slug} - ${resident.name}`;
                    suggestionItem.addEventListener("click", function() {
                        selectedMemberInput.value = `${resident.id} - ${resident.name}`;
                        searchInput.value = "";
                        suggestionsBox.style.display = "none";
                        // Mengisi data id ke hidden input
                        document.getElementById("resident_id").value = resident.id;
                        document.getElementById('amount').focus();
                        setCurrentDate(); // Menampilkan tanggal saat ini
                        // Menampilkan data pembayaran anggota terpilih
                        renderFilteredPaymentTable(resident.id);

                    });
                    suggestionsBox.appendChild(suggestionItem);
                });
                suggestionsBox.style.display = "block";
            } else {
                suggestionsBox.style.display = "none";
            }
        });


        // Sembunyikan saran jika klik di luar kotak pencarian
        document.addEventListener("click", function(event) {
            if (!searchInput.contains(event.target)) {
                suggestionsBox.style.display = "none";
            }
        });

        // Fungsi Menentukan tanggal saat ini dan mengisinya ke input "tanggal_penarikan"
        function setCurrentDate() {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, "0");
            const day = String(currentDate.getDate()).padStart(2, "0");
            const formattedDate = `${year}-${month}-${day}`;
            document.getElementById("tanggal_penarikan").value = formattedDate;
        }

        // Fungsi untuk menyaring dan menampilkan data pembayaran berdasarkan anggota terpilih
        // function renderFilteredPaymentTable(memberId) {
        //     const tbody = document.querySelector("#payment-table tbody");
        //     tbody.innerHTML = "";

        //     // Saring data pembayaran sesuai dengan id anggota
        //     const filteredPayments = payments.filter(payment => payment.memberId === memberId);

        //     if (filteredPayments.length === 0) {
        //         const row = document.createElement("tr");
        //         const cell = document.createElement("td");
        //         cell.colSpan = 4;
        //         cell.textContent = "Tidak ada data pembayaran untuk anggota ini.";
        //         cell.style.textAlign = "center";
        //         row.appendChild(cell);
        //         tbody.appendChild(row);
        //         return;
        //     }

        //     filteredPayments.forEach((payment, index) => {
        //         const row = document.createElement("tr");
        //         const noCell = document.createElement("td");
        //         noCell.textContent = index + 1;
        //         const memberIdCell = document.createElement("td");
        //         memberIdCell.textContent = payment.memberId;
        //         const memberNameCell = document.createElement("td");
        //         memberNameCell.textContent = payment.memberName;
        //         const amountCell = document.createElement("td");
        //         amountCell.textContent = payment.amount.toLocaleString("id-ID");

        //         row.appendChild(noCell);
        //         row.appendChild(memberIdCell);
        //         row.appendChild(memberNameCell);
        //         row.appendChild(amountCell);

        //         tbody.appendChild(row);
        //     });
        // }

        function renderFilteredPaymentTable(resident_id) {
            // Pastikan resident_id ada, lalu panggil API dengan parameter resident_id
            if (resident_id) {
                fetch(`/penarikan-by-residents?resident_id=${resident_id}`)
                    .then(response => response.json()) // Mengubah respons menjadi JSON
                    .then(data => {
                        let tableBody = document.getElementById('penarikans-body');
                        tableBody.innerHTML = ''; // Kosongkan tabel sebelum diisi ulang

                        data.forEach(penarikan => {
                            let row = document.createElement('tr');

                            // Buat kolom untuk data penarikan
                            let idCell = document.createElement('td');
                            idCell.textContent = penarikan.id;
                            row.appendChild(idCell);

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
