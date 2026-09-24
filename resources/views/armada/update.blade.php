@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Update Saldo</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="/armada/{{ $armada->id }}" method="POST" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input name="name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name" value="{{ $armada->name }}">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Max Weight</label>
                    <input name="max_weight" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Max_weight" value="{{ $armada->max_weight }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Length</label>
                    <input name="length" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Length" value="{{ $armada->length }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Widht</label>
                    <input name="width" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Width" value="{{ $armada->width }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Height</label>
                    <input name="height" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Height" value="{{ $armada->height }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Picture</label>
                    <input name="files[]" type="file" class="form-control" multiple id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Picture">
                  </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
        </div>
      </div>
    </div>
</div>
@endsection