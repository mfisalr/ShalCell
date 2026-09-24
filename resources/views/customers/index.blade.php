@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
      <div class="card-header p-4 pb-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
          <div class="shalcell-page-heading"><span class="shalcell-logo">SC</span><div><p class="text-xs text-primary font-weight-bold text-uppercase mb-1">ShalCell</p><h6 class="mb-1">Data Customers</h6><p class="text-sm text-secondary mb-0">Kelola seluruh data transaksi customer.</p></div></div>
          <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('customers.export.excel') }}" class="btn btn-success mb-0"><i class="fas fa-file-excel me-1"></i> Excel</a>
            <a href="{{ route('customers.export.pdf') }}" class="btn btn-danger mb-0"><i class="fas fa-file-pdf me-1"></i> PDF</a>
            <a href="/customers/create" class="btn bg-gradient-primary mb-0"><i class="fas fa-plus me-1"></i> Tambah</a>
          </div>
        </div>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Nama</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nomor</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Saldo Awal</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Saldo Tersisa</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                    <th class="text-secondary opacity-7">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td class="ps-4 font-weight-bold">{{ $customer->nama }}</td>
                        <td>{{ $customer->nomor }}</td>
                        <td>Rp {{ number_format((float) $customer->saldo_awal, 0, ',', '.') }}</td>
                        <td class="font-weight-bold">Rp {{ number_format((float) $customer->saldo_tersisa, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format((float) $customer->nominal, 0, ',', '.') }}</td>
                        <td>{{ $customer->keterangan }}</td>
                        <td>
                          <a href="/customers/{{ $customer->id }}/edit" class="btn btn-warning">Update</a> 
                          <form action="/customers/{{ $customer->id }}" method="POST">
                           @method("DELETE")
                           @csrf
                           <input type="submit" class="btn btn-danger" value="Delete">
                          </form>
                        </td>           
                      </tr>  
                    @endforeach
                  
                </tbody>
              </table>
            </div>
      </div>
    </div>
    
@endsection