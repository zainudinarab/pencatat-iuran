@extends('layouts.rt')

@section('page-title', 'Edit Pembayaran Iuran Bulanan')
@section('back-url', url()->previous())

@push('css')
    <style>
        #iuran_list {
            max-height: 200px;
            overflow-y: auto;
            border-radius: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="container my-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="payment-form" method="POST" action="{{ route('manage-rt.pembayaran.update', $pembayaran->id) }}"
            class="card shadow-sm border-0">
            @csrf
            @method('PUT')

            <input type="hidden" name="collector_id" value="{{ auth()->id() }}">
            <input type="hidden" id="iuran_wajib" name="iuran_wajib">
            <input type="hidden" name="payment_method" value="manual">
            <input type="hidden" name="total_amount" id="total-amount-input">
            <input type="hidden" name="house_id" id="house_id" value="{{ $pembayaran->house_id }}">

            <div class="card-body">
                {{-- Rumah --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Rumah</label>
                    <input type="text" class="form-control bg-light" readonly
                        value="{{ $pembayaran->house->id }} - {{ $pembayaran->house->name }}">
                </div>

                {{-- Daftar Iuran --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Penarikan</label>
                    <div id="iuran_list" class="border rounded p-2">
                        <small class="text-muted">Memuat data...</small>
                    </div>
                    <div class="mt-2 text-end">
                        <span class="fw-bold">Total: Rp <span id="total-amount">0</span></span>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">💾 Update Pembayaran</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        const selectedIuran = @json($selectedIuran);

        document.addEventListener("DOMContentLoaded", function() {
            const houseId = document.getElementById("house_id").value;
            getAllIuran({{ $pembayaran->id }}, true);

        });

        function getAllIuran(house_id, isEditLoad) {
            fetch(`/manage-rt/iuran-all/${house_id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderIuranList(data.data, isEditLoad);
                    } else {
                        document.getElementById("iuran_list").innerHTML =
                            `<p class="text-danger mb-0">Data iuran tidak ditemukan.</p>`;
                    }
                });
        }

        function renderIuranList(list, isEditLoad) {
            const iuranList = document.getElementById("iuran_list");
            const totalAmount = document.getElementById("total-amount");
            const totalAmountInput = document.getElementById("total-amount-input");
            let total = 0;
            let selectedIuranIds = {};

            iuranList.innerHTML = "";

            if (list.length === 0) {
                iuranList.innerHTML = `<p class="text-muted mb-0">Tidak ada iuran.</p>`;
                return;
            }

            list.forEach(iuran => {
                const wrapper = document.createElement("div");
                wrapper.className = "form-check";

                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.className = "form-check-input";
                checkbox.id = `iuran-${iuran.id}`;
                checkbox.value = iuran.id;

                // Kalau sudah dibayar dari API, otomatis tercentang
                if (iuran.is_paid) {
                    checkbox.checked = true;
                    total += iuran.amount;
                    selectedIuranIds[iuran.id] = iuran.amount;
                }

                const label = document.createElement("label");
                label.className = "form-check-label";
                label.setAttribute("for", `iuran-${iuran.id}`);

                const billMonthStr = iuran.bill_month.toString();
                const year = billMonthStr.slice(0, 4); // 4 digit pertama = tahun
                const monthIndex = parseInt(billMonthStr.slice(4, 6), 10) -
                1; // 2 digit terakhir = bulan, dikurangi 1 untuk index array
                const months = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                const monthName = months[monthIndex];

                label.textContent =
                    `${iuran.jenis_iuran_nama} - ${monthName} ${year} - Rp ${iuran.amount.toLocaleString()}`;

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

            totalAmount.textContent = total.toLocaleString();
            totalAmountInput.value = total.toFixed(2);
            document.getElementById('iuran_wajib').value = JSON.stringify(selectedIuranIds);
        }
    </script>
@endpush
