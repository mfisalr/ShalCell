<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Exports\SaldoExport;
use App\Models\Customer;
use App\Models\Saldo;
use App\Models\SaldoHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    private const KETERANGAN = [
        'pulsa',
        'data',
        'token listrik',
        'top up e-wallet',
        'transfer ke bank',
    ];

    public function index()
    {
        $customers = Customer::all();
        return view('customers.index',compact(['customers']));
    }

    public function saldo()
    {
        $customers = Customer::query()->orderBy('nama')->get();
        $saldo = Saldo::query()->firstOrCreate([], ['nominal' => 0]);
        $histories = $saldo->histories()->with('customer')->latest()->get();

        return view('customers.saldo', compact('customers', 'saldo', 'histories'));
    }

    public function tambahSaldo(Request $request)
    {
        $validated = $request->validate([
            'nominal' => ['required', 'numeric', 'gt:0'],
        ]);

        DB::transaction(function () use ($validated): void {
            $saldo = Saldo::query()->lockForUpdate()->firstOrCreate([], ['nominal' => 0]);
            $saldoSebelum = (float) $saldo->nominal;
            $nominal = (float) $validated['nominal'];
            $saldo->update(['nominal' => $saldoSebelum + $nominal]);

            SaldoHistory::create([
                'saldo_id' => $saldo->id,
                'tipe' => 'penambahan',
                'nominal' => $nominal,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldo->nominal,
                'keterangan' => 'Top up saldo utama',
            ]);
        });

        return redirect()->route('saldo.index')->with('success', 'Saldo berhasil ditambahkan.');
    }

    public function exportSaldoExcel()
    {
        return Excel::download(new SaldoExport(), 'riwayat-saldo.xlsx');
    }

    public function exportSaldoPdf()
    {
        $saldo = Saldo::query()->firstOrCreate([], ['nominal' => 0]);
        $histories = $saldo->histories()->with('customer')->latest()->get();

        return Pdf::loadView('customers.saldo-pdf', compact('saldo', 'histories'))
            ->setPaper('a4', 'landscape')
            ->download('riwayat-saldo.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new CustomersExport(), 'data-customers.xlsx');
    }

    public function exportPdf()
    {
        $customers = Customer::query()->orderBy('nama')->get();

        return Pdf::loadView('customers.pdf', compact('customers'))
            ->setPaper('a4', 'portrait')
            ->download('data-customers.pdf');
    }

    public function create()
    {
        return view('customers.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nomor' => ['required', 'string', 'max:50'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['required', 'string', 'in:' . implode(',', self::KETERANGAN)],
        ]);
        $nominal = (float) $request->nominal;

        try {
            DB::transaction(function () use ($request, $nominal): void {
                $saldo = Saldo::query()->lockForUpdate()->firstOrCreate([], ['nominal' => 0]);
                $saldoSebelum = (float) $saldo->nominal;

                if ($nominal > $saldoSebelum) {
                    throw new \RuntimeException('Saldo utama tidak mencukupi.');
                }

                $customer = Customer::create([
                    'nama' => $request->nama,
                    'nomor' => $request->nomor,
                    'saldo_awal' => $saldoSebelum,
                    'saldo_tersisa' => $saldoSebelum - $nominal,
                    'nominal' => $nominal,
                    'keterangan' => $request->keterangan,
                ]);

                $saldo->update(['nominal' => $saldoSebelum - $nominal]);
                $this->recordSaldoHistory($saldo, $customer, 'pengurangan', $nominal, $saldoSebelum, $saldo->nominal, $request->keterangan);
            });
        } catch (\RuntimeException $exception) {
            return back()->withInput()->withErrors(['nominal' => $exception->getMessage()]);
        }

        return redirect('/customers')->with('success','Data Customer berhasil ditambahkan.');

    }
    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('customers.update',compact(['customer']));
    }
    
    public function update(Request $request, $id){
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nomor' => ['required', 'string', 'max:50'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['required', 'string', 'in:' . implode(',', self::KETERANGAN)],
        ]);
        $customer = Customer::find($id);
        $nominalLama = (float) $customer->nominal;
        $nominalBaru = (float) $request->nominal;

        try {
            DB::transaction(function () use ($request, $customer, $nominalLama, $nominalBaru): void {
                $saldo = Saldo::query()->lockForUpdate()->firstOrCreate([], ['nominal' => 0]);
                $selisih = $nominalBaru - $nominalLama;
                $saldoSebelum = (float) $saldo->nominal;

                if ($selisih > $saldoSebelum) {
                    throw new \RuntimeException('Saldo utama tidak mencukupi untuk perubahan nominal.');
                }

                $customer->update([
                    'nama' => $request->nama,
                    'nomor' => $request->nomor,
                    'saldo_awal' => $customer->saldo_awal,
                    'saldo_tersisa' => max(0, (float) $customer->saldo_tersisa - $selisih),
                    'nominal' => $nominalBaru,
                    'keterangan' => $request->keterangan,
                ]);

                $saldo->update(['nominal' => $saldoSebelum - $selisih]);

                if ($selisih != 0.0) {
                    $this->recordSaldoHistory($saldo, $customer, $selisih > 0 ? 'pengurangan' : 'pengembalian', abs($selisih), $saldoSebelum, $saldo->nominal, $request->keterangan);
                }
            });
        } catch (\RuntimeException $exception) {
            return back()->withInput()->withErrors(['nominal' => $exception->getMessage()]);
        }
        return redirect('/customers')->with('success','Data Customer berhasil diupdate.');

    }

    public function destroy($id){
        $customer = Customer::find($id);
        DB::transaction(function () use ($customer): void {
            $saldo = Saldo::query()->lockForUpdate()->firstOrCreate([], ['nominal' => 0]);
            $saldoSebelum = (float) $saldo->nominal;
            $nominal = (float) $customer->nominal;
            $saldo->update(['nominal' => $saldoSebelum + $nominal]);
            $this->recordSaldoHistory($saldo, $customer, 'pengembalian', $nominal, $saldoSebelum, $saldo->nominal, 'Penghapusan transaksi customer');
            $customer->delete();
        });
        return redirect('/customers')->with('success','Data Customer berhasil didelete.');
    }

    private function recordSaldoHistory(Saldo $saldo, Customer $customer, string $tipe, float $nominal, float $saldoSebelum, float $saldoSesudah, string $keterangan): void
    {
        SaldoHistory::create([
            'saldo_id' => $saldo->id,
            'customer_id' => $customer->id,
            'tipe' => $tipe,
            'nominal' => $nominal,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $saldoSesudah,
            'keterangan' => $keterangan,
        ]);
    }

}
