<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembelian</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Pembelian</h2>
    <p>Periode: {{ $start->format('d-m-Y') }} s/d {{ $end->format('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelians as $pembelian)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pembelian->created_at->format('d-m-Y') }}</td>
                    <td>{{ $pembelian->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $pembelian->jumlah }}</td>
                    <td>Rp {{ number_format($pembelian->harga_satuan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($pembelian->harga_satuan * $pembelian->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
