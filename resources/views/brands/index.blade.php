@extends('layouts.app')
@section('content')
  
<div class="card mt-5">
  <h2 class="card-header">Brand</h2>
  <div class="card-body">
          
        @session('success')
            <div class="alert alert-success" role="alert"> {{ $value }} </div>
        @endsession

  
        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th width="80px">No</th>
                    <th>Name</th>
                    <th width="100px">Action</th>
                </tr>
            </thead>
  
            <tbody>
            @forelse ($brands as $brand)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>
                        <form action="{{ route('brands.destroy',$brand->id) }}" method="POST">
           
                            <a class="btn btn-info btn-sm" href="{{ route('brands.show',$brand->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">There are no data.</td>
                </tr>
            @endforelse
            </tbody>
  
        </table>
        
        {!! $brands->links() !!}
  
  </div>
</div>  
@endsection