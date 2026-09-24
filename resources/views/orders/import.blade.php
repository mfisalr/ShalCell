@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header pb-0">
                    <h5 class="mb-1">Manajement Data</h5>
                    <p class="text-sm text-secondary mb-0">Upload file Excel untuk memasukkan banyak data sekaligus.</p>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success text-white" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger text-white" role="alert">
                            <strong>Proses gagal:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('import_failures'))
                        <div class="alert alert-warning" role="alert">
                            <strong>Beberapa baris dilewati:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach (session('import_failures') as $failure)
                                    <li>{{ $failure }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orders.import.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="file" class="form-label">File order (.xlsx)</label>
                            <input id="file" name="file" type="file" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text mt-2">
                                Heading wajib: <code>kode_resi</code>, <code>name</code>, <code>email</code>, <code>address</code>, <code>phone</code>, <code>berat_cbm</code>, <code>status</code>.
                                Kolom <code>name</code> dicocokkan dengan nama perusahaan di database, sedangkan <code>address</code> menjadi alamat tujuan.
                            </div>
                        </div>
                        <button type="submit" class="btn bg-gradient-primary mb-0">
                            <i class="fas fa-file-upload me-2"></i>Import Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
