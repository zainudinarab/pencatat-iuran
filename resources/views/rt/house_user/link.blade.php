@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Hubungkan User dengan Rumah</h2>

        <form action="{{ route('manage-rt.house-user.storeLink') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="user_id">User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="house_id">House</label>
                <select name="house_id" id="house_id" class="form-control" required>
                    @foreach ($houses as $house)
                        <option value="{{ $house->id }}">{{ $house->id }} - {{ $house->address }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="pemilik">Pemilik</option>
                    <option value="penyewa">Penyewa</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
