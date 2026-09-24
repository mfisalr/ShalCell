@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-sm mb-1 font-weight-bold">Saldo tersedia</p>
                            <h5 class="font-weight-bolder mb-0">Rp {{ number_format((float) $saldo->nominal, 0, ',', '.') }}</h5>
                        </div>
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md"><i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i></div>
                    </div>
                    <a href="{{ route('saldo.index') }}" class="text-xs text-primary font-weight-bold">Kelola saldo <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-sm mb-1 font-weight-bold">Total customer</p>
                            <h5 class="font-weight-bolder mb-0">{{ number_format($customers->count()) }}</h5>
                        </div>
                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md"><i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i></div>
                    </div>
                    <a href="{{ route('customers.index') }}" class="text-xs text-info font-weight-bold">Lihat customer <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-sm mb-1 font-weight-bold">Total penambahan</p>
                            <h5 class="font-weight-bolder mb-0">Rp {{ number_format($totalPenambahan, 0, ',', '.') }}</h5>
                        </div>
                        <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md"><i class="ni ni-bold-up text-lg opacity-10" aria-hidden="true"></i></div>
                    </div>
                    <span class="text-xs text-secondary">Dari riwayat saldo</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-sm mb-1 font-weight-bold">Total pengurangan</p>
                            <h5 class="font-weight-bolder mb-0">Rp {{ number_format($totalPengurangan, 0, ',', '.') }}</h5>
                        </div>
                        <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md"><i class="ni ni-bold-down text-lg opacity-10" aria-hidden="true"></i></div>
                    </div>
                    <span class="text-xs text-secondary">Dari transaksi customer</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header pb-0"><h6 class="mb-1">Aktivitas Saldo</h6><p class="text-sm text-secondary mb-0">Ringkasan aktivitas dan transaksi customer.</p></div>
                <div class="card-body p-3">
                    <div class="bg-gradient-dark border-radius-lg py-3 pe-1">
                        <div class="chart"><canvas id="chart-bars" class="chart-canvas" height="170"></canvas></div>
                    </div>
                    <div class="row mt-3 text-center">
                        <div class="col-6"><span class="badge bg-gradient-success mb-2">Penambahan</span><h6 class="mb-0">Rp {{ number_format($totalPenambahan, 0, ',', '.') }}</h6></div>
                        <div class="col-6"><span class="badge bg-gradient-danger mb-2">Pengurangan</span><h6 class="mb-0">Rp {{ number_format($totalPengurangan, 0, ',', '.') }}</h6></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header pb-0"><h6 class="mb-1">Pergerakan Saldo</h6><p class="text-sm text-secondary mb-0">Enam bulan terakhir.</p></div>
                <div class="card-body p-3"><div class="chart"><canvas id="chart-line" class="chart-canvas" height="300"></canvas></div></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between gap-3">
                    <div><h6 class="mb-1">Data Customer</h6><p class="text-sm text-secondary mb-0">{{ $customers->count() }} data tersimpan.</p></div>
                    <div class="d-flex align-items-center gap-2"><input type="search" class="form-control form-control-sm" placeholder="Cari" oninput="filterDashboardTable(this, 'customers-table')" aria-label="Cari customer"><a href="{{ route('customers.index') }}" class="btn btn-sm bg-gradient-primary mb-0 text-nowrap">Semua</a></div>
                </div>
                <div class="card-body px-0 pt-0 pb-2"><div class="table-responsive p-0">
                    <table id="customers-table" class="table align-items-center mb-0"><thead><tr><th>Nama</th><th>Nomor</th><th class="text-end">Nominal</th><th>Keterangan</th></tr></thead><tbody>
                    @forelse ($customers as $customer)
                        <tr><td>{{ $customer->nama }}</td><td>{{ $customer->nomor }}</td><td class="text-end">Rp {{ number_format((float) $customer->nominal, 0, ',', '.') }}</td><td>{{ ucwords($customer->keterangan) }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4">Belum ada data customer.</td></tr>
                    @endforelse
                    </tbody></table>
                </div></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between gap-3">
                    <div><h6 class="mb-1">Riwayat Saldo</h6><p class="text-sm text-secondary mb-0">{{ $totalTransaksi }} transaksi tercatat.</p></div>
                    <div class="d-flex align-items-center gap-2"><input type="search" class="form-control form-control-sm" placeholder="Cari" oninput="filterDashboardTable(this, 'saldo-history-table')" aria-label="Cari riwayat saldo"><a href="{{ route('saldo.index') }}" class="btn btn-sm bg-gradient-primary mb-0 text-nowrap">Semua</a></div>
                </div>
                <div class="card-body px-0 pt-0 pb-2"><div class="table-responsive p-0">
                    <table id="saldo-history-table" class="table align-items-center mb-0"><thead><tr><th>Tanggal</th><th>Tipe</th><th class="text-end">Nominal</th><th>Customer</th></tr></thead><tbody>
                    @forelse ($histories as $history)
                        <tr><td>{{ $history->created_at->format('d/m/Y H:i') }}</td><td><span class="badge bg-gradient-{{ $history->tipe === 'pengurangan' ? 'danger' : 'success' }}">{{ ucfirst($history->tipe) }}</span></td><td class="text-end">Rp {{ number_format((float) $history->nominal, 0, ',', '.') }}</td><td>{{ $history->customer?->nama ?? '-' }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4">Belum ada riwayat saldo.</td></tr>
                    @endforelse
                    </tbody></table>
                </div></div>
            </div>
        </div>
    </div>

    <script>
        function filterDashboardTable(input, tableId) {
            const query = input.value.toLowerCase().trim();
            document.querySelectorAll(`#${tableId} tbody tr`).forEach((row) => {
                row.hidden = !row.textContent.toLowerCase().includes(query);
            });
        }
    </script>
</div>
@endsection
