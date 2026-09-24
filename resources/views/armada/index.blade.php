@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <h6>Saldo</h6>
            <a href="/armada/create" class="btn btn-primary float-end">Add</a>
          </div>
          <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Max Weight</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dimensi</th>
                    <th class="text-secondary opacity-7">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($armada as $arm)
                    <tr>    
                        <td>
                          @if($arm->pictures->isNotEmpty())
                              <img class="img-thumbnail" width="170" src="/uploads/{{ $arm->pictures()->first()->filename }}" alt="">
                            @endif
                            {{ $arm->name }}
                        </td>
                        <td>{{ $arm->max_weight }}kg</td>      
                        <td>{{ $arm->length }}cm <strong>X</strong>W{{ $arm->length }}cm <strong>X</strong>H{{ $arm->length }}cm</td>      
                        <td>{{ $arm->width }}</td>
                        <td>{{ $arm->height }}</td>  
                        <td>
                          <a href="/armada/{{ $arm->id }}/edit" class="btn btn-warning">Update</a> 
                          <form class="hapus" action="/armada/{{ $arm->id }}" method="POST">
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
      </div>
    </div>
    
@endsection