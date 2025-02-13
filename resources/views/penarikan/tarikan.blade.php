@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Daftar Penarikan</h2>


        <hr>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Resident ID</th>
                    <th>Tarikan 1</th>
                    <th>Tarikan 2</th>
                    <th>Tarikan 3</th>
                    <th>Tarikan 4</th>
                    <th>Tarikan 5</th>
                    <th>Tarikan 6</th>
                    <th>Tarikan 7</th>
                    <th>Tarikan 8</th>
                    <th>Tarikan 9</th>
                    <th>Tarikan 10</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($tableData as $row)
                    <tr>
                        <td>{{ $row['no'] }}</td>
                        <td>{{ $row['resident_id'] }}</td>
                        <td>{{ $row['tarikan1'] ?? '' }}</td>
                        <td>{{ $row['tarikan2'] ?? '' }}</td>
                        <td>{{ $row['tarikan3'] ?? '' }}</td>
                        <td>{{ $row['tarikan4'] ?? '' }}</td>
                        <td>{{ $row['tarikan5'] ?? '' }}</td>
                        <td>{{ $row['tarikan6'] ?? '' }}</td>
                        <td>{{ $row['tarikan7'] ?? '' }}</td>
                        <td>{{ $row['tarikan8'] ?? '' }}</td>
                        <td>{{ $row['tarikan9'] ?? '' }}</td>
                        <td>{{ $row['tarikan10'] ?? '' }}</td>
                        <!-- Sesuaikan dengan jumlah tarikan yang mungkin ada -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
