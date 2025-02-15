@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Pengeluaran RT</h2>
        <form action="{{ route('manage-rt.pengeluaran.update', $pengeluaranRt->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="rt_id">RT</label>
                <select name="rt_id" id="rt_id" class="form-control">
                    @foreach ($rts as $rt)
                        <option value="{{ $rt->id }}" @if ($pengeluaranRt->rt_id == $rt->id) selected @endif>
                            {{ $rt->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Jumlah Pengeluaran</label>
                <input type="text" name="amount" id="amount" class="form-control"
                    value="{{ old('amount', $pengeluaranRt->amount) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Keterangan</label>
                <textarea name="description" id="description" class="form-control" required>{{ old('description', $pengeluaranRt->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="approved_by">Bendahara</label>
                <select name="approved_by" id="approved_by" class="form-control">
                    @foreach ($bendahara as $user)
                        <option value="{{ $user->id }}" @if ($pengeluaranRt->approved_by == $user->id) selected @endif>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-warning mt-3">Perbarui</button>
        </form>
    </div>
@endsection
