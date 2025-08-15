@extends('layouts.rt')

@section('content')
    <div class="container">
        <h4 class="mb-3">Laporan Setoran Petugas per RT</h4>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>RT</th>
                    <th>Petugas</th>
                    <th class="text-end">Total Penerimaan</th>
                    <th class="text-end">Sudah Disetorkan</th>
                    <th class="text-end">Belum Disetorkan</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($rts as $rt)
                    @php
                        $petugasList = $rt->petugasList ?? collect(); // pastikan pakai data yang sudah dihitung per petugas
                    @endphp

                    @if ($petugasList->count())
                        @foreach ($petugasList as $petugas)
                            <tr>
                                <td>{{ $rt->name ?? '-' }}</td>
                                <td>{{ $petugas->name ?? '-' }}</td>
                                <td class="text-end">{{ number_format($petugas->total_penerimaan ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($petugas->total_setor ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($petugas->total_belum_setor ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('manage-rt.shared.petugas.detail', $petugas->id) }}"
                                        class="btn btn-sm btn-primary">
                                        Detail
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>{{ $rt->name ?? '-' }}</td>
                            <td><em>Tidak ada petugas</em></td>
                            <td class="text-end">0</td>
                            <td class="text-end">0</td>
                            <td class="text-end">0</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data RT</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
