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
        <form action="{{ route('penarikan.update', $penarikan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="petugas_id" class="form-label">Petugas</label>
                <select name="petugas_id" id="petugas_id" class="form-control" disabled>
                    @foreach ($petugas as $user)
                        <option value="{{ $user->id }}" {{ $penarikan->petugas_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="petugas_id" value="{{ $penarikan->petugas_id }}" hidden>
            </div>

            <div class="mb-3">
                <label for="resident_id" class="form-label">Resident</label>
                <select name="resident_id" id="resident_id" class="form-control" disabled>
                    @foreach ($residents as $resident)
                        <option value="{{ $resident->id }}"
                            {{ $penarikan->resident_id == $resident->id ? 'selected' : '' }}>
                            {{ $resident->name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="resident_id" value="{{ $penarikan->resident_id }}" hidden>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Jumlah Penarikan</label>
                <!-- Input yang menampilkan format rupiah dengan Rp -->
                <input type="text" id="amount" class="form-control"
                    value="{{ 'Rp ' . number_format($penarikan->amount, 0, ',', '.') }}" required>

                <!-- Input tersembunyi untuk menyimpan nilai numerik -->
                <input type="hidden" name="amount_numeric" id="amount_numeric" value="{{ $penarikan->amount }}">
            </div>

            <div class="mb-3">
                <label for="tanggal_penarikan" class="form-label">Tanggal Penarikan</label>
                <input type="date" name="tanggal_penarikan" id="tanggal_penarikan" class="form-control"
                    value="{{ $penarikan->tanggal_penarikan }}" required>
            </div>


            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('penarikan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>




    </div>
@endsection

@push('js')
    <script>
        // Fokuskan input amount saat halaman pertama kali dimuat


        // Format input amount sebagai Rupiah
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value;

            // Hapus semua karakter selain angka dan 'Rp'
            value = value.replace(/[^0-9]/g, '');

            // Format angka menjadi format rupiah
            const formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Menambahkan 'Rp' di depan dan set value di input amount
            e.target.value = 'Rp ' + formattedValue;

            // Update input hidden dengan nilai numerik yang sebenarnya
            document.getElementById('amount_numeric').value = value;
        });
        // window.onload = function() {
        //     document.getElementById('amount').focus();
        // };
    </script>
@endpush
