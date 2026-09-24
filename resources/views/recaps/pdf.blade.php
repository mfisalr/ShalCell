<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Data</title>
    <style>
        @page { margin: 28px 30px; }
        body { color: #344767; font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        .topline { width: 100%; border-bottom: 3px solid #7928ca; margin-bottom: 16px; }
        .brand { display: inline-block; margin-bottom: 8px; padding: 7px 10px; border-radius: 8px; color: #fff; background: #7928ca; font-size: 11px; font-weight: bold; letter-spacing: 1px; }
        h1 { margin: 0 0 5px; color: #344767; font-size: 20px; }
        .meta { margin-bottom: 16px; color: #8392a6; font-size: 9px; }
        .summary { width: 100%; margin-bottom: 18px; border-collapse: separate; border-spacing: 7px 0; }
        .summary td { width: 25%; padding: 10px; border: 1px solid #dbe4f0; background: #f8f9fa; }
        .summary .label { display: block; margin-bottom: 5px; color: #8392a6; font-size: 8px; text-transform: uppercase; }
        .summary strong { color: #7928ca; font-size: 12px; }
        table.data { width: 100%; border-collapse: collapse; }
        .data th { padding: 8px 6px; border: 1px solid #cbd5e1; color: #fff; background: #344767; text-align: left; font-size: 8px; text-transform: uppercase; }
        .data td { padding: 7px 6px; border: 1px solid #dbe4f0; }
        .data tr:nth-child(even) td { background: #f8f9fa; }
        .right { text-align: right; white-space: nowrap; }
        .muted { color: #8392a6; }
    </style>
</head>
<body>
    <div class="topline"></div>
    <div class="brand">SC &nbsp; ShalCell</div>
    <h1>Rekap Data</h1>
    <div class="meta">Periode {{ $periodeMulai->format('d/m/Y') }} - {{ $periodeSelesai->format('d/m/Y') }} | Dicetak {{ now()->format('d/m/Y H:i') }}</div>
    <table class="summary">
        <tr><td><span class="label">Jumlah transaksi</span><strong>{{ $histories->count() }}</strong></td><td><span class="label">Total penambahan</span><strong>Rp {{ number_format($totalPenambahan, 0, ',', '.') }}</strong></td><td><span class="label">Total pengurangan</span><strong>Rp {{ number_format($totalPengurangan, 0, ',', '.') }}</strong></td><td><span class="label">Saldo saat ini</span><strong>Rp {{ number_format((float) $saldo->nominal, 0, ',', '.') }}</strong></td></tr>
    </table>
    <table class="data">
        <thead><tr><th>Tanggal</th><th>Tipe</th><th>Customer</th><th class="right">Nominal</th><th class="right">Saldo Sesudah</th><th>Keterangan</th></tr></thead>
        <tbody>
        @forelse ($histories as $history)
            <tr><td>{{ $history->created_at->format('d/m/Y H:i') }}</td><td>{{ ucfirst($history->tipe) }}</td><td>{{ $history->customer?->nama ?? '-' }}</td><td class="right">Rp {{ number_format((float) $history->nominal, 0, ',', '.') }}</td><td class="right">Rp {{ number_format((float) $history->saldo_sesudah, 0, ',', '.') }}</td><td>{{ ucwords($history->keterangan) }}</td></tr>
        @empty
            <tr><td colspan="6" class="muted">Tidak ada data pada periode ini.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
