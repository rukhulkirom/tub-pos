    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Buku Besar</title>
        <style>
            .table {
                width: 100%;
                border-collapse: collapse;
            }
            .table, .table th, .table td {
                border: 1px solid black;
            }
            .table th, .table td {
                padding: 8px;
                text-align: left;
            }
            .text-right {
                text-align: right;
            }
            .text-center {
                text-align: center;
            }
        </style>
    </head>
    <body>
        @php
            $bulanIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $bulanInt = intval($bulan);

            $accountName = ($filter == 'penjualan') ? 'Penjualan' : 'Kas';
            $accountNumber = ($filter == 'penjualan') ? '411' : '111';
        @endphp
        <h3 class="text-center">Toko Usaha Baru</h3>
        <h3 class="text-center">Buku Besar</h3>
        <h3 class="text-center">Periode {{ $bulanIndo[$bulanInt] ?? '' }} {{ $tahun }}</h3>
        <h3>Nama Akun: {{ $accountName }}</h3>
        <h3>Nomor Akun: {{ $accountNumber }}</h3>
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center;" rowspan="2">Tanggal</th>
                    <th style="text-align: center;" rowspan="2">Akun</th>
                    <th style="text-align: center;" rowspan="2">Ref</th>
                    <th style="text-align: center;" rowspan="2">Debit</th>
                    <th style="text-align: center;" rowspan="2">Kredit</th>
                    <th style="text-align: center;" colspan="2">Saldo</th>
                </tr>
                <tr>
                    <th style="text-align: center;">Debit</th>
                    <th style="text-align: center;">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    <tr>
                        <td style="text-align: center;">{{ $item['tanggal'] }}</td>
                        <td>{{ $item['akun'] }}</td>
                        <td style="text-align: center;">{{ $item['ref'] }}</td>
                        <td style="text-align: right;">{{ $item['debit'] }}</td>
                        <td style="text-align: right;">{{ $item['kredit'] }}</td>
                        <td style="text-align: right;">{{ $item['saldo_debit'] }}</td>
                        <td style="text-align: right;">{{ $item['saldo_kredit'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
    </html>
