@extends('layouts.app')
  
@section('content')

<div class="card mt-5">
  <h2 class="card-header">Show Product</h2>
  <div class="card-body">
  
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.products.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        <a class="btn btn-success btn-sm" href="{{ route('admin.specifications.edit', $product->id) }}"><i class="fa fa-cog"></i> Manage Specifications</a>
    </div>
  
    <div class="row mt-4">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Name:</strong> <br/>
                {{ $product->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Brand:</strong> <br/>
                {{ $product->brand->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
            <div class="form-group">
                <strong>Description:</strong> <br/>
                {{ $product->description }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 mt-3">
            <div class="form-group">
                <strong>Stock:</strong> <br/>
                {{ $product->stock }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 mt-3">
            <div class="form-group">
                <strong>Price:</strong> <br/>
                {{ $product->price }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
            <div class="form-group">
                <strong>Image:</strong> <br/>
                <img src="{{ $product->image_url }}" width="200">
            </div>
        </div>
        
        <!-- Product Specifications -->
        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Product Specifications</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($product->specifications->isEmpty())
                        <p class="text-muted">No specifications have been added for this product.</p>
                        <a href="{{ route('admin.specifications.edit', $product->id) }}" class="btn btn-sm btn-primary">Add Specifications</a>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Specification</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->specifications as $spec)
                                    <tr>
                                        <td>{{ $spec->specificationType->display_name }}</td>
                                        <td>{{ $spec->formatted_value }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
  
  </div>
</div>
@endsection