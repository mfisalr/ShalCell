<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Saldo</title>
    <style>
        @page { margin: 28px; }
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { margin: 0 0 5px; color: #344767; font-size: 20px; }
        .meta { margin-bottom: 15px; color: #6b7280; }
        .summary { margin-bottom: 18px; padding: 10px; border: 1px solid #dbe4f0; background: #f8f9fa; }
        .summary strong { color: #7928ca; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 8px 6px; border: 1px solid #cbd5e1; color: #fff; background: #7928ca; text-align: left; }
        td { padding: 7px 6px; border: 1px solid #cbd5e1; }
        tr:nth-child(even) td { background: #f8f9fa; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Saldo ShalCell</h1>
    <div class="meta">Dicetak pada {{ now()->format('d/m/Y H:i') }}</div>
    <div class="summary">Saldo utama saat ini: <strong>Rp {{ number_format((float) $saldo->nominal, 0, ',', '.') }}</strong></div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Customer</th>
                <th class="right">Nominal</th>
                <th class="right">Saldo Sebelum</th>
                <th class="right">Saldo Sesudah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($histories as $history)
                <tr>
                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($history->tipe) }}</td>
                    <td>{{ $history->customer?->nama ?? '-' }}</td>
                    <td class="right">Rp {{ number_format((float) $history->nominal, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format((float) $history->saldo_sebelum, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format((float) $history->saldo_sesudah, 0, ',', '.') }}</td>
                    <td>{{ ucwords($history->keterangan) }}</td>
                </tr>
            @empty
                <tr><td colspan="7">Belum ada riwayat saldo.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
