@extends('layouts.app')
  
@section('content')

<div class="card mt-5">
  <h2 class="card-header">Show Product</h2>
  <div class="card-body">
  
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.products.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
  
    <div class="row mt-4">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong> <br/>
                {{ $product->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Description:</strong> <br/>
                {{ $product->description }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Stock:</strong> <br/>
                {{ $product->stock }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Price:</strong> <br/>
                {{ $product->price }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Brand:</strong> <br/>
                {{ $product->brand->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Image:</strong> <br/>
                @if ($product->image)
                    <img src="{{ $product->image }}" width="200">
                @else
                    No image available
                @endif
            </div>
        </div>
    </div>
  
  </div>
</div>
@endsection