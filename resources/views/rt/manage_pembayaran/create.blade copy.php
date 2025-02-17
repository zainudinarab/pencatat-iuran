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
</style>
@endpush
@section('content')
<h2>Pembayaran Iuran Bulanan</h2>
<div class="mb-3">
    <label for="member-search" class="form-label">Cari Rumah</label>
    <input type="text" id="houses-search" placeholder="Ketik Nama atau No Rumah" autocomplete="off">
    <div id="suggestions" class="autocomplete-items mt-2" style="display: none;"></div>
</div>
<div class="mb-3">
    <label for="selected-member" class="form-label">Rumah Terpilih</label>
    <input type="text" id="selected-houses" class="form-control" readonly placeholder="Rumah belum dipilih">
    <input type="text" name="house_id" id="haose_id" class="form-control" hidden>
</div>
<div class="border p-3 rounded-3 shadow-sm overflow-auto" id="iuran_list">
</div>
<div class="mb-3">
    <label for="iuran_list" class="form-label">Pilih Iuran yang Belum Dibayar:</label>
    <div class="border p-3 rounded-3 shadow-sm overflow-auto" id="iuran_list" style="max-height: 200px;">
        <!-- Daftar iuran yang belum dibayar akan dimuat di sini -->
        <!-- Contoh isi dummy untuk memastikan ada cukup konten -->
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="iuran_ids[]" value="1">
            <label class="form-check-label">Iuran 1 - Bulan 202502 - Rp 100.000</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="iuran_ids[]" value="2">
            <label class="form-check-label">Iuran 2 - Bulan 202502 - Rp 200.000</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="iuran_ids[]" value="3">
            <label class="form-check-label">Iuran 3 - Bulan 202502 - Rp 300.000</label>
        </div>
        <!-- Tambahkan lebih banyak item untuk menguji scroll -->
    </div>
</div>
<div class="mb-3">
    <label>Pilih Penarikan yang Belum Disetorkan:</label>
    <div class="checkbox-list" id="penarikan_list">
        <!-- Data penarikan yang belum disetorkan akan dimuat di sini -->
        @foreach ($houses as $hoause)
        <div class="checkbox-item">
            <input type="checkbox" name="penarikan_ids[]" value="{{ $hoause->id }}">
            {{ $hoause->id }}- {{ $hoause->name }} - Rp
            {{ number_format($hoause->amount, 0) }}
        </div>
        @endforeach
    </div>
</div>
<label for="houseInput">Masukkan Rumah ID atau Nama:</label>
<input type="text" id="houseInput" placeholder="Cari ID atau Nama..." oninput="showAutocomplete()" />
<div id="autocompleteList" class="autocomplete-items hidden"></div>

<div id="billSection" class="hidden">
    <h3>Tagihan untuk <span id="selectedName"></span>:</h3>
    <div id="billList"></div>
    <div class="total">Total yang harus dibayar: Rp <span id="totalAmount">0</span></div>
    <button id="payButton" class="button" onclick="processPayment()" disabled>Proses Pembayaran</button>
</div>

<div id="confirmationSection" class="confirmation-section hidden">
    <h3>Konfirmasi Pembayaran:</h3>
    <p>Anda akan membayar:</p>
    <ul id="confirmationList"></ul>
    <p>Total: Rp <span id="confirmationTotal">0</span></p>
    <button class="button" onclick="confirmPayment()">Ya, Bayar Sekarang</button>
    <button class="button" style="background-color: #dc3545;" onclick="cancelPayment()">Batal</button>
</div>

<div id="invoiceSection" class="invoice-section hidden">
    <h3>Bukti Pembayaran</h3>
    <div id="invoiceContent"></div>
    <div class="invoice-actions">
        <button class="button" onclick="downloadInvoice()">Download Invoice</button>
        <button class="button" onclick="sendEmail()">Kirim Email</button>
    </div>
</div>
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
        // Kosongkan elemen checklist
        iuranList.innerHTML = "";

        // Jika tidak ada data
        if (iuranData.length === 0) {
            iuranList.innerHTML = "<p>Tidak ada iuran yang belum dibayar.</p>";
            return;
        }

        // Membuat checklist untuk setiap iuran
        iuranData.forEach(iuran => {
            const checkboxItem = document.createElement("div");
            checkboxItem.classList.add("form-check");

            const checkbox = document.createElement("input");
            checkbox.classList.add("form-check-input");
            checkbox.type = "checkbox";
            checkbox.id = `iuran_${iuran.id}`;
            checkbox.value = iuran.id;
            checkbox.setAttribute("data-amount", iuran.amount); // Menyimpan amount

            const label = document.createElement("label");
            label.classList.add("form-check-label");
            label.setAttribute("for", checkbox.id);
            label.textContent =
                `Iuran ${iuran.jenis_iuran_id} - Bulan ${iuran.bill_month} - Rp ${iuran.amount}`;

            checkboxItem.appendChild(checkbox);
            checkboxItem.appendChild(label);
            iuranList.appendChild(checkboxItem);
        });
    }
    const data = [{
            id: "L01",
            name: "Ali Rahman",
            unpaid_bills: ["Januari 2023", "Februari 2023", "Maret 2023"]
        },
        {
            id: "L04",
            name: "Budi Santoso",
            unpaid_bills: ["Februari 2023", "Maret 2023"]
        },
        {
            id: "L05",
            name: "Ali Mustofa",
            unpaid_bills: ["Januari 2023"]
        }
    ];

    let selectedHouse = null;
    let selectedBills = [];

    function showAutocomplete() {
        const input = document.getElementById("houseInput").value.toLowerCase();
        const list = document.getElementById("autocompleteList");
        list.innerHTML = "";
        list.classList.add("hidden");

        if (input.length === 0) return;

        const matches = houses.filter(item =>
            item.id.includes(input) || item.name.toLowerCase().includes(input)
        );

        if (matches.length > 0) {
            matches.forEach(item => {
                const div = document.createElement("div");
                div.textContent = `${item.id} - ${item.name} `;
                div.onclick = () => selectHouse(item);
                list.appendChild(div);
            });
            list.classList.remove("hidden");
        }
    }

    function selectHouse(house) {
        selectedHouse = house;
        document.getElementById("houseInput").value = `${house.id} - ${house.name} `;
        document.getElementById("autocompleteList").classList.add("hidden");

        const billSection = document.getElementById("billSection");
        const billList = document.getElementById("billList");
        billList.innerHTML = "";

        selectedBills = [];
        data.unpaid_bills.forEach(bill => {
            const div = document.createElement("div");
            div.className = "bill-item";
            div.innerHTML = `
    <input type="checkbox" id="${bill}" value="${bill}" onchange="updateTotal()">
    <label for="${bill}">${bill} - Rp 10.000</label>
  `;
            billList.appendChild(div);
        });

        document.getElementById("selectedName").textContent = house.name;
        document.getElementById("totalAmount").textContent = "0";
        document.getElementById("payButton").disabled = true;
        billSection.classList.remove("hidden");
    }

    function updateTotal() {
        const checkboxes = document.querySelectorAll("#billList input[type='checkbox']");
        selectedBills = [];
        let total = 0;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedBills.push(checkbox.value);
                total += 10000;
            }
        });

        document.getElementById("totalAmount").textContent = total;
        document.getElementById("payButton").disabled = selectedBills.length === 0;
    }

    function processPayment() {
        const confirmationSection = document.getElementById("confirmationSection");
        const confirmationList = document.getElementById("confirmationList");
        confirmationList.innerHTML = "";

        selectedBills.forEach(bill => {
            const li = document.createElement("li");
            li.textContent = `${bill} - Rp 10.000`;
            confirmationList.appendChild(li);
        });

        document.getElementById("confirmationTotal").textContent = selectedBills.length * 10000;
        confirmationSection.classList.remove("hidden");
        document.getElementById("billSection").classList.add("hidden");
    }

    function confirmPayment() {
        const invoiceSection = document.getElementById("invoiceSection");
        const invoiceContent = document.getElementById("invoiceContent");
        invoiceContent.innerHTML = `
  <p><strong>Nama:</strong> ${selectedHouse.name}</p>
  <p><strong>ID Rumah:</strong> ${selectedHouse.id}</p>
  <p><strong>Tagihan yang Dibayar:</strong></p>
  <ul>
    ${selectedBills.map(bill => `<li>${bill} - Rp 10.000</li>`).join("")}
  </ul>
  <p><strong>Total:</strong> Rp ${selectedBills.length * 10000}</p>
  <p><strong>Tanggal:</strong> ${new Date().toLocaleDateString()}</p>
`;

        invoiceSection.classList.remove("hidden");
        document.getElementById("confirmationSection").classList.add("hidden");
    }

    function downloadInvoice() {
        const invoiceContent = document.getElementById("invoiceContent").innerHTML;
        const blob = new Blob([invoiceContent], {
            type: "text/html"
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = `invoice_${selectedHouse.id}_${new Date().toISOString().split("T")[0]}.html`;
        a.click();
        URL.revokeObjectURL(url);
    }

    function sendEmail() {
        alert("Fitur kirim email belum diimplementasikan. Anda bisa menggunakan layanan email eksternal.");
    }

    function cancelPayment() {
        document.getElementById("confirmationSection").classList.add("hidden");
        document.getElementById("billSection").classList.remove("hidden");
    }
</script>
@endpush