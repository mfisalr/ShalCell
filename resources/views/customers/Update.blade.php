@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
      <div class="card mb-4">
        <div class="card-header p-4 pb-3"><div class="shalcell-page-heading"><span class="shalcell-logo">SC</span><div><p class="text-xs text-primary font-weight-bold text-uppercase mb-1">ShalCell</p><h6 class="mb-1">Edit Customer</h6><p class="text-sm text-secondary mb-0">Perbarui data transaksi customer.</p></div></div></div>
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="/customers/{{ $customer->id }}" method="POST">
                @method('put')
                @csrf
                <div class="form-group">
                  <label for="nama">Nama</label>
                  <input name="nama" type="text" class="form-control" id="nama" placeholder="Nama" value="{{ $customer->nama }}">
                </div>
                <div class="form-group">
                    <label for="nomor">Nomor</label>
                    <input name="nomor" type="text" class="form-control" id="nomor" placeholder="Nomor" value="{{ $customer->nomor }}">
                  </div>
                    <div class="form-group">
                    <label for="nominal">Nominal</label>
                      <input name="nominal" type="number" min="0" step="0.01" class="form-control" id="nominal" placeholder="Nominal transaksi" value="{{ old('nominal', $customer->nominal) }}" required>
                  </div>
                  <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <select name="keterangan" class="form-control" id="keterangan" required>
                      @foreach (['pulsa', 'data', 'token listrik', 'top up e-wallet', 'transfer ke bank'] as $option)
                        <option value="{{ $option }}" @selected($customer->keterangan === $option)>{{ ucwords($option) }}</option>
                      @endforeach
                    </select>
                  </div>
                <div class="d-flex justify-content-end gap-2"><a href="{{ route('customers.index') }}" class="btn btn-light mb-0">Batal</a><button type="submit" class="btn bg-gradient-primary mb-0">Simpan perubahan</button></div>
              </form>
        </div>
      </div>
    </div>
    </div>
</div>
@endsection