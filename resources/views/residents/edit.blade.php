@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Resident</h1>

        <!-- Menampilkan Pesan Sukses -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form untuk edit resident -->
        <form action="{{ route('residents.update', $resident->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Menyatakan bahwa ini adalah request PUT untuk update -->

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $resident->name) }}" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control"
                    value="{{ old('phone_number', $resident->phone_number) }}" required>
            </div>


            <div class="form-group">
                <label for="blok">Blok</label>
                <input type="text" name="blok" id="blok" class="form-control"
                    value="{{ old('blok', $resident->blok) }}" required maxlength="1" pattern="[A-Z]"
                    oninput="this.value = this.value.toUpperCase()">
            </div>

            <div class="form-group">
                <label for="nomor_rumah">Nomor Rumah</label>
                <input type="text" name="nomor_rumah" id="nomor_rumah" class="form-control"
                    value="{{ old('nomor_rumah', $resident->nomor_rumah) }}" required maxlength="2" pattern="\d{1,2}">
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}"
                    readonly>
                @error('slug')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="RT">RT</label>
                <input type="text" name="RT" id="RT" class="form-control"
                    value="{{ old('RT', $resident->RT) }}" required>
            </div>

            <div class="form-group">
                <label for="RW">RW</label>
                <input type="text" name="RW" id="RW" class="form-control"
                    value="{{ old('RW', $resident->RW) }}" required>
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea name="address" id="address" class="form-control" required>{{ old('address', $resident->address) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Perbarui</button>
        </form>
    </div>
@endsection
@push('js')
    <script>
        document.getElementById('blok').addEventListener('input', generateSlug);
        document.getElementById('nomor_rumah').addEventListener('input', generateSlug);

        function generateSlug() {
            var blok = document.getElementById('blok').value.toUpperCase(); // Ubah blok menjadi huruf kapital
            var nomorRumah = document.getElementById('nomor_rumah').value;

            // Tambahkan '0' di depan jika nomor rumah hanya satu digit
            if (nomorRumah.length == 1) {
                nomorRumah = '0' + nomorRumah;
            }

            var slug = blok + nomorRumah;
            document.getElementById('slug').value = slug; // Isi field slug dengan slug yang dihasilkan
        }
    </script>
@endpush
