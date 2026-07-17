<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jurnal Umum</title>
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <style>
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .header-center {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h3 class="text-center">Toko Usaha Baru</h3>
    <h3 class="text-center">Jurnal Umum</h3>
    <h3 class="text-center">
        Periode {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
    </h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="header-center">Tanggal</th>
                <th class="header-center">Akun</th>
                <th class="header-center">Ref</th>
                <th class="header-center">Debit</th>
                <th class="header-center">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td class="header-center">{{ $row['tanggal'] }}</td>
                    <td>{{ $row['akun'] }}</td>
                    <td class="header-center">{{ $row['ref'] }}</td>
                    <td class="text-right">{{ $row['debit'] }}</td>
                    <td class="text-right">{{ $row['kredit'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
