@extends('layouts.app')

@push('css')
@endpush
@section('content')
    <div class="container">
        <h1>Tambah Resident</h1>

        <!-- Menampilkan Pesan Sukses -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form untuk input resident -->
        <form action="{{ route('residents.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" class="form-control" value="{{ old('id') }}" readonly>
                @error('id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                    required>
            </div>




            <div class="form-group">
                <label for="blok">Blok</label>
                <input type="text" name="blok" id="blok" class="form-control" value="{{ old('blok') }}"
                    required maxlength="1" pattern="[A-L]" oninput="this.value = this.value.toUpperCase()">
            </div>

            <div class="form-group">
                <label for="nomor_rumah">Nomor Rumah</label>
                <input type="number" name="nomor_rumah" id="nomor_rumah" class="form-control"
                    value="{{ old('nomor_rumah') }}" required maxlength="2" pattern="\d{1,2}">
            </div>

            <div class="form-group">
                <label for="RT">RT</label>
                <input type="number" name="RT" id="RT" class="form-control" value="{{ old('RT') }}"
                    required maxlength="2" pattern="\d{1,2}">
            </div>

            <div class="form-group">
                <label for="RW">RW</label>
                <input type="number" name="RW" id="RW" class="form-control" value="{{ old('RW') }}"
                    required maxlength="2" pattern="\d{1,2}">
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea name="address" id="address" class="form-control" required>{{ old('address') }}</textarea>
            </div>
            <div class="form-group">
                <label for="phone_number">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control"
                    value="{{ old('phone_number') }}">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
@push('js')
    <script>
        document.getElementById('blok').addEventListener('input', generateId);
        document.getElementById('nomor_rumah').addEventListener('input', generateId);



        function generateId() {
            var blok = document.getElementById('blok').value.toUpperCase(); // Ubah blok menjadi huruf kapital
            var nomorRumah = document.getElementById('nomor_rumah').value;

            // Tambahkan '0' di depan jika nomor rumah hanya satu digit
            if (nomorRumah.length == 1) {
                nomorRumah = '0' + nomorRumah;
            }

            var id = blok + nomorRumah;
            document.getElementById('id').value = id; // Isi field slug dengan slug yang dihasilkan
        }
    </script>
@endpush
