@extends('layouts.rt')

@section('page-title', 'Pembayaran Iuran Bulanan')
@section('back-url', url()->previous())
@push('css')
    <style>
        #suggestions {
            max-height: 200px;
            overflow-y: auto;
            border-radius: 0.25rem;
        }
    </style>
@endpush
@section('content')
    <div class="container my-4">

        {{-- Pencarian Rumah --}}
        <div class="mb-3">
            <label for="houses-search" class="form-label fw-bold">Cari Rumah</label>
            <input type="text" id="houses-search" class="form-control" placeholder="Ketik Nama atau No Rumah"
                autocomplete="off">
            <div id="suggestions" class="list-group position-absolute w-100 shadow-sm mt-1"
                style="z-index: 1050; display:none; max-height:200px; overflow-y:auto;">
            </div>

        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="payment-form" method="POST" action="{{ route('manage-rt.pembayaran.store') }}"
            class="card shadow-sm border-0">
            @csrf
            <input type="hidden" name="collector_id" value="{{ auth()->user()->id }}">
            <input type="hidden" id="iuran_wajib" name="iuran_wajib">
            <input type="hidden" name="payment_method" value="manual">
            <input type="hidden" name="total_amount" id="total-amount-input">

            <div class="card-body">
                {{-- Rumah Terpilih --}}
                <div class="mb-3">
                    <label for="selected-houses" class="form-label fw-bold">Rumah Terpilih</label>
                    <input type="text" id="selected-houses" class="form-control bg-light" readonly
                        placeholder="Rumah belum dipilih">
                    <input type="hidden" name="house_id" id="haose_id">
                </div>

                {{-- Daftar Iuran --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Penarikan yang Belum Disetorkan</label>
                    <div id="iuran_list" class="border rounded p-2" style="max-height:200px; overflow-y:auto;">
                        <small class="text-muted">Silakan pilih rumah terlebih dahulu</small>
                    </div>
                    <div class="mt-2 text-end">
                        <span class="fw-bold">Total: Rp <span id="total-amount">0</span></span>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">💾 Simpan Pembayaran</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        const houses = @json($houses);
        const searchInput = document.getElementById("houses-search");
        const suggestionsBox = document.getElementById("suggestions");
        const selectedHousesInput = document.getElementById("selected-houses");
        const iuranList = document.getElementById("iuran_list");

        searchInput.addEventListener("input", function() {
            const query = searchInput.value.toLowerCase();
            if (query.length === 0) {
                suggestionsBox.style.display = "none";
                return;
            }
            const filtered = houses.filter(h =>
                String(h.id).toLowerCase().includes(query) ||
                h.name.toLowerCase().includes(query)
            );
            suggestionsBox.innerHTML = "";
            if (filtered.length > 0) {
                filtered.forEach(h => {
                    const item = document.createElement("button");
                    item.type = "button";
                    item.className = "list-group-item list-group-item-action";
                    item.textContent = `${h.id} - ${h.name}`;
                    item.onclick = () => {
                        selectedHousesInput.value = `${h.id} - ${h.name}`;
                        document.getElementById("haose_id").value = h.id;
                        suggestionsBox.style.display = "none";
                        getIuranBelumDibayar(h.id);
                    };
                    suggestionsBox.appendChild(item);
                });
                suggestionsBox.style.display = "block";
            } else {
                suggestionsBox.style.display = "none";
            }
        });

        document.addEventListener("click", function(e) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.style.display = "none";
            }
        });

        function getIuranBelumDibayar(house_id) {
            fetch(`/manage-rt/belum-dibayar/${house_id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderIuranList(data.data);
                    } else {
                        iuranList.innerHTML = `<p class="text-danger mb-0">Data iuran tidak ditemukan.</p>`;
                    }
                });
        }

        function renderIuranList(list) {
            iuranList.innerHTML = "";
            const totalAmount = document.getElementById("total-amount");
            const totalAmountInput = document.getElementById("total-amount-input");
            let total = 0;
            let selectedIuranIds = {};

            if (list.length === 0) {
                iuranList.innerHTML = `<p class="text-muted mb-0">Tidak ada iuran yang belum dibayar.</p>`;
                return;
            }

            list.forEach(iuran => {
                const wrapper = document.createElement("div");
                wrapper.className = "form-check";

                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.className = "form-check-input";
                checkbox.id = `iuran-${iuran.id}`;
                checkbox.name = "iuran_ids[]";
                checkbox.value = iuran.id;

                const label = document.createElement("label");
                label.className = "form-check-label";
                label.setAttribute("for", `iuran-${iuran.id}`);

                const billMonth = iuran.bill_month.toString();
                const year = billMonth.substring(0, 4);
                const month = billMonth.substring(4, 6);
                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                    "September", "Oktober", "November", "Desember"
                ];
                const monthName = months[parseInt(month) - 1];
                label.textContent = `${iuran.jenis_iuran_id} - ${monthName} ${year} - Rp ${iuran.amount}`;

                checkbox.addEventListener("change", () => {
                    if (checkbox.checked) {
                        total += iuran.amount;
                        selectedIuranIds[iuran.id] = iuran.amount;
                    } else {
                        total -= iuran.amount;
                        delete selectedIuranIds[iuran.id];
                    }
                    totalAmount.textContent = total.toLocaleString();
                    totalAmountInput.value = total.toFixed(2);
                    document.getElementById('iuran_wajib').value = JSON.stringify(selectedIuranIds);
                });

                wrapper.appendChild(checkbox);
                wrapper.appendChild(label);
                iuranList.appendChild(wrapper);
            });
        }
    </script>
@endpush
