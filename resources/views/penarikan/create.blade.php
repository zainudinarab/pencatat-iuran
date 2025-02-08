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

            <!-- Form Grup: Anggota Terpilih -->
            <div class="mb-3">
                <label for="selected-member" class="form-label">Anggota Terpilih</label>
                <input type="text" id="selected-member" class="form-control" readonly
                    placeholder="Anggota belum dipilih">
                <input type="text" name="resident_id" id="resident_id" class="form-control">
            </div>

            <!-- Form Grup: Jumlah Pembayaran -->
            <div class="mb-3">
                <label for="amount" class="form-label">Jumlah Pembayaran (Rp)</label>
                <input type="number" name="amount" id="amount" class="form-control"
                    placeholder="Masukkan Jumlah Pembayaran" required>

            </div>

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
        <table id="payment-table" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Jumlah Pembayaran (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan ditampilkan di sini -->
            </tbody>
        </table>
    </div>
@endsection

@push('js')
    {{-- <script>
        // Contoh data anggota

        const members2 = [{
                id: "A001",
                name: "Andi Pratama"
            },
            {
                id: "A002",
                name: "Budi Santoso"
            },
            {
                id: "A003",
                name: "Citra Dewi"
            },
            {
                id: "A004",
                name: "Dewi Lestari"
            },
            {
                id: "A005",
                name: "Eko Prasetyo"
            }
        ];

        // Variabel untuk menyimpan data pembayaran
        let payments = [{
                memberId: "A001",
                memberName: "Andi Pratama",
                amount: 500000
            },

            {
                memberId: "A001",
                memberName: "Andi Pratama",
                amount: 600000
            },
            {
                memberId: "A001",
                memberName: "Andi Pratama",
                amount: 700000
            },
            {
                memberId: "A002",
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
            const filteredMembers = members.filter(member =>
                member.id.toLowerCase().includes(query) || member.name.toLowerCase().includes(query)
            );
            if (filteredMembers.length > 0) {
                suggestionsBox.innerHTML = "";
                filteredMembers.forEach(member => {
                    const suggestionItem = document.createElement("div");
                    suggestionItem.textContent = `${member.id} - ${member.name}`;
                    suggestionItem.addEventListener("click", function() {
                        selectedMemberInput.value = `${member.id} - ${member.name}`;
                        searchInput.value = "";
                        suggestionsBox.style.display = "none";
                        //nampilkan tanggal pembayaran yaran anggota terpilih
                        document.getElementById("resident_id").value = `${member.id}`;
                        setCurrentDate();
                        // Menampilkan data pembayaran anggota terpilih
                        renderFilteredPaymentTable(member.id);
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
        // Validasi form sebelum submit
        // document.getElementById("payment-form").addEventListener("submit", function(event) {
        //     event.preventDefault();

        //     const selectedMember = selectedMemberInput.value.trim();
        //     const amount = document.getElementById("amount").value.trim();

        //     if (!selectedMember || !amount) {
        //         alert("Harap lengkapi semua data!");
        //         return;
        //     }

        //     // Parsing data anggota terpilih
        //     const [memberId, memberName] = selectedMember.split(" - ");
        //     // Menambahkan data pembayaran ke array
        //     payments.push({
        //         memberId,
        //         memberName,
        //         amount: parseInt(amount)
        //     });
        //     // Menampilkan data pembayaran di tabel
        //     renderPaymentTable();
        //     // Reset form
        //     selectedMemberInput.value = "";
        //     document.getElementById("amount").value = "";
        // });

        // // Fungsi untuk merender tabel pembayaran
        // function renderPaymentTable() {
        //     const tbody = document.querySelector("#payment-table tbody");
        //     tbody.innerHTML = "";

        //     payments.forEach((payment, index) => {
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
        //// fungsi Menentukan tanggal saat ini dan mengisinya ke input "tanggal_penarikan"
        function setCurrentDate() {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, "0");
            const day = String(currentDate.getDate()).padStart(2, "0");
            const formattedDate = `${year}-${month}-${day}`;
            document.getElementById("tanggal_penarikan").value = formattedDate;
        }


        // Fungsi untuk menyaring dan menampilkan data pembayaran berdasarkan anggota terpilih
        function renderFilteredPaymentTable(memberId) {
            const tbody = document.querySelector("#payment-table tbody");
            tbody.innerHTML = "";

            const filteredPayments = payments.filter(payment => payment.memberId === memberId);

            if (filteredPayments.length === 0) {
                const row = document.createElement("tr");
                const cell = document.createElement("td");
                cell.colSpan = 4;
                cell.textContent = "Tidak ada data pembayaran untuk anggota ini.";
                cell.style.textAlign = "center";
                row.appendChild(cell);
                tbody.appendChild(row);
                return;
            }

            filteredPayments.forEach((payment, index) => {
                const row = document.createElement("tr");

                const noCell = document.createElement("td");
                noCell.textContent = index + 1;

                const memberIdCell = document.createElement("td");
                memberIdCell.textContent = payment.memberId;

                const memberNameCell = document.createElement("td");
                memberNameCell.textContent = payment.memberName;

                const amountCell = document.createElement("td");
                amountCell.textContent = payment.amount.toLocaleString("id-ID");

                row.appendChild(noCell);
                row.appendChild(memberIdCell);
                row.appendChild(memberNameCell);
                row.appendChild(amountCell);

                tbody.appendChild(row);
            });
        }
    </script> --}}

    <script>
        // Menggunakan data `residents` yang sudah dikirim dari controller
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
                String(resident.id).toLowerCase().includes(query) ||
                resident.name.toLowerCase().includes(query)
            );

            if (filteredResidents.length > 0) {
                suggestionsBox.innerHTML = "";
                filteredResidents.forEach(resident => {
                    const suggestionItem = document.createElement("div");
                    suggestionItem.textContent = `${resident.slug} - ${resident.name}`;
                    suggestionItem.addEventListener("click", function() {
                        selectedMemberInput.value = `${resident.slug} - ${resident.name}`;
                        searchInput.value = "";
                        suggestionsBox.style.display = "none";
                        // Mengisi data id ke hidden input
                        document.getElementById("resident_id").value = resident.id;
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
        function renderFilteredPaymentTable(memberId) {
            const tbody = document.querySelector("#payment-table tbody");
            tbody.innerHTML = "";

            // Saring data pembayaran sesuai dengan id anggota
            const filteredPayments = payments.filter(payment => payment.memberId === memberId);

            if (filteredPayments.length === 0) {
                const row = document.createElement("tr");
                const cell = document.createElement("td");
                cell.colSpan = 4;
                cell.textContent = "Tidak ada data pembayaran untuk anggota ini.";
                cell.style.textAlign = "center";
                row.appendChild(cell);
                tbody.appendChild(row);
                return;
            }

            filteredPayments.forEach((payment, index) => {
                const row = document.createElement("tr");

                const noCell = document.createElement("td");
                noCell.textContent = index + 1;

                const memberIdCell = document.createElement("td");
                memberIdCell.textContent = payment.memberId;

                const memberNameCell = document.createElement("td");
                memberNameCell.textContent = payment.memberName;

                const amountCell = document.createElement("td");
                amountCell.textContent = payment.amount.toLocaleString("id-ID");

                row.appendChild(noCell);
                row.appendChild(memberIdCell);
                row.appendChild(memberNameCell);
                row.appendChild(amountCell);

                tbody.appendChild(row);
            });
        }
    </script>
@endpush
