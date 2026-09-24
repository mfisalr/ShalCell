@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="shalcell-logo">SC</span>
        <div><p class="text-xs text-primary font-weight-bold text-uppercase mb-1">ShalCell</p><h5 class="mb-1">Saldo & Riwayat Transaksi</h5><p class="text-sm text-secondary mb-0">Pantau saldo utama dan seluruh pergerakan transaksi.</p></div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-xl-5 col-md-6">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body p-4">
                    <p class="text-white text-sm mb-2 opacity-8">Saldo utama tersedia</p>
                    <h2 class="text-white mb-0">Rp {{ number_format((float) $saldo->nominal, 0, ',', '.') }}</h2>
                    <p class="text-white text-sm mt-3 mb-0 opacity-8">Saldo berkurang otomatis saat transaksi customer dibuat.</p>
                </div>
            </div>
        </div>
        <div class="col-xl-7 col-md-6">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h6 class="mb-3">Tambah saldo</h6>
                    <form action="{{ route('saldo.store') }}" method="POST" class="row align-items-end g-3">
                        @csrf
                        <div class="col-md-8">
                            <label for="nominal-saldo" class="form-label">Nominal yang ditambahkan</label>
                            <input id="nominal-saldo" name="nominal" type="number" min="1" step="0.01" class="form-control @error('nominal') is-invalid @enderror" placeholder="Contoh: 100000" required>
                            @error('nominal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn bg-gradient-primary w-100 mb-0">Tambah saldo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
            <div class="card-header p-4 pb-3">
            <div><h6 class="mb-1">Riwayat saldo</h6><p class="text-sm text-secondary mb-0">Setiap penambahan, pengurangan, dan pengembalian saldo tercatat di sini.</p></div>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="{{ route('saldo.export.excel') }}" class="btn btn-success mb-0">
                    <i class="fas fa-file-excel me-1"></i> Excel
                </a>
                <a href="{{ route('saldo.export.pdf') }}" class="btn btn-danger mb-0">
                    <i class="fas fa-file-pdf me-1"></i> PDF
                </a>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Saldo Sesudah</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                <td><span class="badge bg-gradient-{{ $history->tipe === 'pengurangan' ? 'danger' : 'success' }}">{{ ucfirst($history->tipe) }}</span></td>
                                <td>{{ $history->customer?->nama ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format((float) $history->nominal, 0, ',', '.') }}</td>
                                <td class="text-end font-weight-bold">Rp {{ number_format((float) $history->saldo_sesudah, 0, ',', '.') }}</td>
                                <td>{{ ucwords($history->keterangan) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4">Belum ada riwayat saldo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
