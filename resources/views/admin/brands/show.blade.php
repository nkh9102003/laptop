@extends('layouts.admin')
  
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Show Brand</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('admin.brands.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            @if($brand->logo)
                <div class="mb-4">
                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="img-fluid rounded">
                </div>
            @endif
        </div>
        <div class="col-md-8">
            <div class="form-group mb-3">
                <strong>Name:</strong>
                {{ $brand->name }}
            </div>
            <div class="form-group mb-3">
                <strong>Description:</strong>
                {{ $brand->description }}
            </div>
            <div class="form-group mb-3">
                <strong>Created At:</strong>
                {{ $brand->created_at->format('F d, Y') }}
            </div>
        </div>
    </div>
</div>
@endsection