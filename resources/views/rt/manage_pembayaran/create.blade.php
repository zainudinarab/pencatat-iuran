@extends('layouts.app')
@push('css')
    <style>
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .autocomplete-items {
            position: relative;
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-top: none;
            background: #fff;
            z-index: 1000;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .autocomplete-items div:hover {
            background-color: #f0f8ff;
        }

        .bill-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .bill-item input[type="checkbox"] {
            margin-right: 10px;
        }

        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .hidden {
            display: none;
        }

        .confirmation-section {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .confirmation-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .confirmation-section li {
            padding: 5px 0;
        }

        .invoice-section {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .invoice-section h3 {
            margin-top: 0;
        }

        .invoice-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .invoice-actions button {
            flex: 1;
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
    <h2>Pembayaran Iuran Bulanan</h2>
    <div class="mb-3">
        <label for="member-search" class="form-label">Cari Rumah</label>
        <input type="text" id="houses-search" placeholder="Ketik Nama atau No Rumah" autocomplete="off">
        <div id="suggestions" class="autocomplete-items mt-2" style="display: none;"></div>
    </div>
    {{-- is error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="payment-form" method="POST" action="{{ route('manage-rt.pembayaran.store') }}">
        @csrf
        <input type="text" name="collector_id" value="{{ auth()->user()->id }}" hidden>
        <input type="text" name="rt_id" value="{{ $roleData['rt_id'] }}" hidden>
        <div class="mb-3">
            <label for="selected-member" class="form-label">Rumah Terpilih</label>
            <input type="text" id="selected-houses" class="form-control" readonly placeholder="Rumah belum dipilih">
            <input type="text" name="house_id" id="haose_id" class="form-control" hidden>
        </div>
        <input type="hidden" id="iuran_wajib" name="iuran_wajib">

        <div class="mb-3">
            <label>Pilih Penarikan yang Belum Disetorkan:</label>
            <div class="checkbox-list" id="iuran_list">
                <!-- Data penarikan yang belum disetorkan akan dimuat di sini -->
            </div>
            <div id="total-section">
                <p><strong>Total: Rp <span id="total-amount">0</span></strong></p>
            </div>
        </div>
        {{-- payment_method --}}
        <input type="text" name="payment_method" value="manual" hidden>
        {{-- <div class="mb-3">
            <label for="payment_method">Metode Pembayaran:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="manual">Manual</option>
                <option value="midtrans">Midtrans</option>
                <option value="xendit">Xendit</option>
            </select>
        </div> --}}
        <!-- Input untuk menampilkan total amount -->
        <input type="text" name="total_amount" id="total-amount-input" hidden>
        {{-- submit bostrap --}}
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection

@push('js')
    <script>
        const houses = @json($houses); // Mengonversi data `residents` dari PHP ke JavaScript
        const searchInput = document.getElementById("houses-search");
        const suggestionsBox = document.getElementById("suggestions");
        const selectedHousesInput = document.getElementById("selected-houses");
        const iuranList = document.getElementById("iuran_list");


        // Fungsi untuk menampilkan saran anggota
        searchInput.addEventListener("input", function() {
            const query = searchInput.value.toLowerCase();
            if (query.length === 0) {
                suggestionsBox.style.display = "none";
                return;
            }

            // Pastikan 'resident.id' diubah ke string sebelum menggunakan 'toLowerCase'
            const filteredHouses = houses.filter(houses =>
                String(houses.id).toLowerCase().includes(query) ||
                houses.name.toLowerCase().includes(query)
            );

            if (filteredHouses.length > 0) {
                suggestionsBox.innerHTML = "";
                filteredHouses.forEach(houses => {
                    const suggestionItem = document.createElement("div");
                    suggestionItem.textContent = `${houses.id} - ${houses.name}`;
                    suggestionItem.addEventListener("click", function() {
                        selectedHousesInput.value = `${houses.id} - ${houses.name}`;
                        searchInput.value = "";
                        suggestionsBox.style.display = "none";
                        // Mengisi data id ke hidden input
                        document.getElementById("haose_id").value = houses.id;
                        // document.getElementById('amount').focus();
                        // setCurrentDate(); // Menampilkan tanggal saat ini

                        // Menampilkan data iuran yang belum dibayar
                        getIuranBelumDibayar(houses.id);
                        // renderFilteredPaymentTable(houses.id);

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

        function getIuranBelumDibayar(house_id) {
            // Mengirimkan request ke API untuk mendapatkan data iuran yang belum dibayar
            fetch(`/manage-rt/belum-dibayar/${house_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderIuranList(data.data);
                    } else {
                        iuranList.innerHTML = "<p>Data iuran tidak ditemukan.</p>";
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    iuranList.innerHTML = "<p>Terjadi kesalahan dalam mengambil data iuran.</p>";
                });
        }

        // Fungsi untuk merender iuran yang belum dibayar sebagai checklist
        function renderIuranList(iuranData) {
            const iuranList = document.getElementById("iuran_list");
            const totalAmount = document.getElementById("total-amount");
            const totalAmountInput = document.getElementById("total-amount-input");

            // Kosongkan elemen checklist
            iuranList.innerHTML = "";

            // Jika tidak ada data
            if (iuranData.length === 0) {
                iuranList.innerHTML = "<p>Tidak ada iuran yang belum dibayar.</p>";
                return;
            }

            let total = 0; // Inisialisasi total
            let selectedIuranIds = {}; // Menyimpan pasangan id => amount

            iuranData.forEach(iuran => {
                const checkboxItem = document.createElement("div");
                checkboxItem.classList.add("checkbox-item");

                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.name = "iuran_ids[]"; // Untuk mengirim id terpilih
                checkbox.value = iuran.id;

                const amountInput = document.createElement("input");
                amountInput.type = "hidden";
                amountInput.name = `amounts[${iuran.id}]`; // Menyimpan amount
                amountInput.value = iuran.amount;

                const label = document.createElement("span");

                // Pisahkan tahun dan bulan
                const billMonth = iuran.bill_month.toString();
                const year = billMonth.substring(0, 4); // Ambil tahun (4 digit pertama)
                const month = billMonth.substring(4, 6); // Ambil bulan (2 digit terakhir)
                // Array nama bulan dalam Bahasa Indonesia
                const months = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                const monthName = months[parseInt(month) - 1]; // Mengonversi angka bulan ke nama bulan
                const formattedMonth = `${monthName} ${year}`;
                label.textContent =
                    ` ${iuran.jenis_iuran_id} - ${formattedMonth} - Rp ${iuran.amount}`;

                checkboxItem.appendChild(checkbox);
                checkboxItem.appendChild(label);
                iuranList.appendChild(checkboxItem);

                // Event listener untuk checkbox
                checkbox.addEventListener("change", function() {
                    // Jika checkbox dipilih, tambahkan jumlahnya ke total, jika tidak, kurangi
                    if (checkbox.checked) {
                        total += iuran.amount;
                        selectedIuranIds[iuran.id] = iuran.amount; // Menyimpan id => amount
                    } else {
                        total -= iuran.amount;
                        delete selectedIuranIds[iuran.id]; // Menghapus pasangan id => amount
                    }

                    totalAmount.textContent = total.toLocaleString(); // Format ke Rp
                    totalAmountInput.value = total.toFixed(2); // Update input tersembunyi untuk total

                    // Menambahkan data pasangan id => amount ke dalam input tersembunyi yang lain
                    document.getElementById('iuran_wajib').value = JSON.stringify(selectedIuranIds);
                });
            });
        }
    </script>
@endpush
