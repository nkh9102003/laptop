@extends('layouts.app')
    
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Brand</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('admin.brands.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $brand->name }}" class="form-control" placeholder="Brand Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Description:</strong>
                    <textarea class="form-control" name="description" placeholder="Description">{{ $brand->description }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Current Logo:</strong>
                    @if($brand->logo)
                        <div class="mb-2">
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="max-height: 100px;">
                        </div>
                    @else
                        <p>No logo uploaded</p>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Update Logo:</strong>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
@endsection