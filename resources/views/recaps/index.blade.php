@extends('layouts.master')

@section('content')
<style>
    .recap-page { max-width: 1500px; margin: 0 auto; }
    .recap-hero {
        padding: 1.5rem 1.75rem;
        border: 1px solid #e9ecef;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(52, 71, 103, .06);
    }
    .recap-hero .shalcell-logo { box-shadow: none; }
    .recap-hero h4 { margin: .2rem 0 .35rem; color: #344767; }
    .recap-hero p { margin: 0; color: #8392a6; font-size: .86rem; }
    .recap-period { padding: .65rem .9rem; border-radius: 10px; color: #7928ca; background: #f7f1ff; font-size: .78rem; font-weight: 700; }
    .recap-filter { border: 1px solid #e9ecef; border-radius: 14px; background: #fff; }
    .recap-filter label { margin-bottom: .5rem; color: #344767; font-size: .75rem; font-weight: 700; }
    .recap-filter .form-control { min-height: 46px; border-color: #e9ecef; border-radius: 10px; }
    .recap-filter .form-control:focus { border-color: #7928ca; box-shadow: 0 0 0 3px rgba(121, 40, 202, .1); }
    .recap-filter .btn { min-height: 46px; border-radius: 10px; }
    .period-field { position: relative; }
    .period-field i { position: absolute; top: 15px; left: 14px; z-index: 1; color: #8392a6; }
    .period-field .form-control { padding-left: 40px; }
    .period-help { min-height: 17px; margin-top: .45rem; color: #8392a6; font-size: .72rem; }
    .recap-action-wrap { padding-bottom: 17px; }
    .recap-stat { position: relative; height: 100%; border: 1px solid #e9ecef; border-radius: 14px; background: #fff; box-shadow: 0 6px 18px rgba(52, 71, 103, .04); overflow: hidden; }
    .recap-stat::before { position: absolute; top: 0; bottom: 0; left: 0; width: 4px; background: #7928ca; content: ''; }
    .recap-stat.stat-success::before { background: #2dce89; }
    .recap-stat.stat-danger::before { background: #f5365c; }
    .recap-stat.stat-info::before { background: #11cdef; }
    .recap-stat .stat-label { margin-bottom: .45rem; color: #8392a6; font-size: .74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
    .recap-stat .stat-value { margin: 0; color: #344767; font-size: 1.15rem; font-weight: 700; }
    .recap-stat .stat-caption { margin: .4rem 0 0; color: #8392a6; font-size: .76rem; }
    .recap-table thead th { padding: .85rem .9rem; color: #8392a6; background: #f8f9fa; font-size: .68rem; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; }
    .recap-table tbody td { padding: .85rem .9rem; color: #344767; font-size: .82rem; vertical-align: middle; }
    .recap-table tbody tr:last-child td { border-bottom: 0; }
    .recap-table .amount { font-weight: 700; white-space: nowrap; }
    .recap-table .date { color: #8392a6; white-space: nowrap; }
    .recap-actions { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .6rem; }
    .recap-actions .btn { display: inline-flex; align-items: center; justify-content: center; min-height: 46px; margin: 0; white-space: nowrap; }
    @media (max-width: 575px) {
        .recap-hero { padding: 1.25rem; }
        .recap-period { width: 100%; text-align: center; }
        .recap-actions { grid-template-columns: 1fr; }
    }
</style>

<div class="container-fluid py-4 recap-page">
    <section class="recap-hero mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="shalcell-logo" aria-label="Logo ShalCell">SC</span>
                <div>
                    <p class="text-uppercase font-weight-bold mb-1">ShalCell</p>
                    <h4>Rekap Data</h4>
                    <p>Ringkasan transaksi dari riwayat saldo berdasarkan periode.</p>
                </div>
            </div>
            <div class="recap-period">{{ $periodeMulai->format('d M Y') }} - {{ $periodeSelesai->format('d M Y') }}</div>
        </div>
    </section>

    <section class="card recap-filter mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div><h6 class="mb-1">Pilih periode</h6><p class="text-sm text-secondary mb-0">Tampilkan rekap berdasarkan bulan atau tahun.</p></div>
            </div>
            <form method="GET" action="{{ route('recaps.index') }}" class="row align-items-end g-3">
                <div class="col-lg-3 col-md-4">
                    <label for="mode">Tampilkan berdasarkan</label>
                    <select id="mode" name="mode" class="form-control" onchange="this.form.submit()">
                        <option value="monthly" @selected($mode === 'monthly')>Per bulan</option>
                        <option value="yearly" @selected($mode === 'yearly')>Per tahun</option>
                    </select>
                    <div class="period-help">Pilih jenis rekap.</div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <label for="period">Pilih periode</label>
                    <div class="period-field"><i class="fas fa-calendar-alt" aria-hidden="true"></i><input id="period" name="period" type="{{ $mode === 'monthly' ? 'month' : 'number' }}" value="{{ $period }}" @if ($mode === 'yearly') min="2000" max="2100" placeholder="Contoh: 2026" @endif class="form-control" required></div>
                    <div class="period-help">{{ $mode === 'monthly' ? 'Pilih bulan dan tahun laporan.' : 'Masukkan tahun laporan.' }}</div>
                </div>
                <div class="col-lg-2 col-md-4 recap-action-wrap"><button type="submit" class="btn bg-gradient-primary w-100 mb-0">Lihat</button></div>
                <div class="col-lg-4 recap-action-wrap"><div class="recap-actions"><a href="{{ route('recaps.export.excel', ['mode' => $mode, 'period' => $period]) }}" class="btn btn-success mb-0"><i class="fas fa-file-excel me-1"></i> Export Excel</a><a href="{{ route('recaps.export.pdf', ['mode' => $mode, 'period' => $period]) }}" class="btn btn-danger mb-0"><i class="fas fa-file-pdf me-1"></i> Cetak PDF</a></div></div>
            </form>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6"><div class="recap-stat stat-info p-3"><p class="stat-label">Jumlah transaksi</p><p class="stat-value">{{ number_format($histories->count()) }}</p><p class="stat-caption">Aktivitas pada periode ini</p></div></div>
        <div class="col-xl-3 col-md-6"><div class="recap-stat stat-success p-3"><p class="stat-label text-success">Total penambahan</p><p class="stat-value">Rp {{ number_format($totalPenambahan, 0, ',', '.') }}</p><p class="stat-caption">Saldo yang masuk</p></div></div>
        <div class="col-xl-3 col-md-6"><div class="recap-stat stat-danger p-3"><p class="stat-label text-danger">Total pengurangan</p><p class="stat-value">Rp {{ number_format($totalPengurangan, 0, ',', '.') }}</p><p class="stat-caption">Saldo yang terpakai</p></div></div>
        <div class="col-xl-3 col-md-6"><div class="recap-stat p-3"><p class="stat-label">Saldo saat ini</p><p class="stat-value">Rp {{ number_format((float) $saldo->nominal, 0, ',', '.') }}</p><p class="stat-caption">Saldo utama ShalCell</p></div></div>
    </section>

    <section class="card">
        <div class="card-header px-4 pt-4 pb-0"><h6 class="mb-1">Detail transaksi</h6><p class="text-sm text-secondary mb-0">Data riwayat saldo untuk periode yang dipilih.</p></div>
        <div class="card-body px-0 pt-3 pb-0"><div class="table-responsive">
            <table class="table recap-table align-items-center mb-0">
                <thead><tr><th>Tanggal</th><th>Tipe</th><th>Customer</th><th class="text-end">Nominal</th><th class="text-end">Saldo sesudah</th><th>Keterangan</th></tr></thead>
                <tbody>
                @forelse ($histories as $history)
                    <tr><td class="date">{{ $history->created_at->format('d/m/Y H:i') }}</td><td><span class="badge bg-gradient-{{ $history->tipe === 'pengurangan' ? 'danger' : 'success' }}">{{ ucfirst($history->tipe) }}</span></td><td>{{ $history->customer?->nama ?? '-' }}</td><td class="text-end amount">Rp {{ number_format((float) $history->nominal, 0, ',', '.') }}</td><td class="text-end amount">Rp {{ number_format((float) $history->saldo_sesudah, 0, ',', '.') }}</td><td>{{ ucwords($history->keterangan) }}</td></tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 text-secondary">Tidak ada data pada periode ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div></div>
    </section>
</div>
@endsection
