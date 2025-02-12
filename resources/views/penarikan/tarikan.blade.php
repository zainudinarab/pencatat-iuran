<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Penarikan</title>
</head>

<body>
    <h1>Data Penarikan Dikelompokkan</h1>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Resident ID</th>
                <th>Tarikan 1</th>
                <th>Tarikan 2</th>
                <th>Tarikan 3</th>
                <th>Tarikan 4</th>
                <th>Tarikan 5</th>
                <!-- Sesuaikan dengan jumlah kolom tarikan yang diinginkan -->
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
                    <!-- Sesuaikan dengan jumlah tarikan yang mungkin ada -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
