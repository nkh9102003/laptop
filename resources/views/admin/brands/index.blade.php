@extends('layouts.app')
   
@section('content')
  
<div class="card mt-5">
  <h2 class="card-header">Brand Management</h2>
  <div class="card-body">

        <form action="{{ route('admin.brands.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search brands..." value="{{ request()->input('search') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-success btn-sm" href="{{ route('admin.brands.create') }}"><i class="fa fa-plus"></i> Create New Brand</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th width="80px">ID</th>
                    <th>Name</th>
                    <th width="250px">Action</th>
                </tr>
            </thead>
  
            <tbody>
            @forelse ($brands as $brand)
                <tr>
                    <td>{{ $brand->id }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>
                        <form action="{{ route('admin.brands.destroy',$brand->id) }}" method="POST">
             
                            <a class="btn btn-info btn-sm" href="{{ route('admin.brands.show',$brand->id) }}"><i class="fa-solid fa-list"></i> Show</a>
              
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.brands.edit',$brand->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
             
                            @csrf
                            @method('DELETE')
                
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">There are no data.</td>
                </tr>
            @endforelse
            </tbody>
  
        </table>
        
        {!! $brands->appends(request()->query())->links() !!}
  
  </div>
</div>  
@endsection