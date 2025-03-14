@extends('layouts.app')
@section('content')
  
<div class="card mt-5">
  <h2 class="card-header">Product Management</h2>
  <div class="card-body">
          
      <form action="{{ route('admin.products.index') }}" method="GET" class="mb-4">
          <div class="row">
              <div class="col-md-4">
                  <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request()->input('search') }}">
              </div>
              <div class="col-md-4">
                  <select name="brand" class="form-control">
                      <option value="">All Brands</option>
                      @foreach($brands as $brand)
                          <option value="{{ $brand->id }}" {{ request()->input('brand') == $brand->id ? 'selected' : '' }}>
                              {{ $brand->name }}
                          </option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-4">
                  <button class="btn btn-outline-secondary" type="submit">Search</button>
                  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Reset</a>
              </div>
          </div>
      </form>
      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <a class="btn btn-success btn-sm" href="{{ route('admin.products.create') }}"> <i class="fa fa-plus"></i> Create New Product</a>
      </div>
  
      <table class="table table-bordered table-striped mt-4">
      <thead>
              <tr>
                  <th width="80px">ID</th>
                  <th>Name</th>
                  <th>Image</th>
                  <th>Description</th>
                  <th>Stock</th>
                  <th>Price</th>
                  <th>Brand</th>
                  <th width="250px">Action</th>
              </tr>
          </thead>
  
          <tbody>
          @foreach ($products as $product)
              <tr>
                  <td>{{ $product->id }}</td>
                  <td>{{ $product->name }}</td>
                  <td><img src="{{ $product->image_url }}" width="100px" alt=""></td>
                  <td>{{ $product->description }}</td>
                  <td>{{ $product->stock }}</td>
                  <td>{{ $product->price }}</td>
                  <td>{{ $product->brand->name }}</td>
                  <td>
                      <form id="deleteForm{{ $product->id }}" action="{{ route('admin.products.destroy',$product->id) }}" method="POST">
           
                          <a class="btn btn-info btn-sm" href="{{ route('admin.products.show',$product->id) }}"><i class="fa-solid fa-list"></i> Show</a>
           
                          <a class="btn btn-primary btn-sm" href="{{ route('admin.products.edit',$product->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
           
                          @csrf
                          @method('DELETE')
           
                          <button onclick="confirmDelete({{ $product->id }})" type="button" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                      </form>
                  </td>
              </tr>
          @endforeach
          </tbody>
      </table>
      
      {!! $products->appends(request()->query())->links() !!}
  
  </div>
</div>  
<script>
    function confirmDelete(productId) {
        if (confirm("Are you sure you want to delete this product?")) {
            document.getElementById('deleteForm'+productId).submit();
        } else {
            return false;
        }
    }
</script>
@endsection