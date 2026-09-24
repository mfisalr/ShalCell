<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Customers</title>
    <style>
        @page { margin: 28px; }
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { margin: 0 0 6px; color: #172b4d; font-size: 20px; }
        .meta { margin-bottom: 18px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 9px 8px; border: 1px solid #cbd5e1; color: #fff; background: #2563eb; text-align: left; }
        td { padding: 8px; border: 1px solid #cbd5e1; }
        tr:nth-child(even) td { background: #f8fafc; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h1>Data Customers</h1>
    <div class="meta">Dicetak pada {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Nomor</th>
                <th class="right">Nominal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $customer->nama }}</td>
                    <td>{{ $customer->nomor }}</td>
                    <td class="right">Rp {{ number_format((float) $customer->nominal, 0, ',', '.') }}</td>
                    <td>{{ ucwords($customer->keterangan) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada data customer.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
