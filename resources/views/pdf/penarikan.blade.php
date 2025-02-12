<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Penarikan PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Data Penarikan</h1>
    <table>
        <thead>
            <tr>
                <th>ID Penarikan</th>
                <th>Nama Petugas</th>
                <th>Nama Resident</th>
                <th>Jumlah Penarikan</th>
                <th>Tanggal Penarikan</th>
                <th>Jumlah Setoran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penarikans as $penarikan)
                <tr>
                    <td>{{ $penarikan->id }}</td>
                    <td>{{ $penarikan->petugas->name ?? 'N/A' }}</td>
                    <td>{{ $penarikan->resident->name ?? 'N/A' }}</td>
                    <td>{{ $penarikan->amount }}</td>
                    <td>{{ $penarikan->tanggal_penarikan->format('Y-m-d') }}</td>
                    <td>{{ $penarikan->setoran->amount ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
