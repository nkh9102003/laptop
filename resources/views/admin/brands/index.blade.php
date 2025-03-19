@extends('layouts.admin')
   
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Brands Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('admin.brands.create') }}"> Create New Brand</a>
            </div>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-6">
            <form action="{{ route('admin.brands.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search brands..." value="{{ request()->input('search') }}">
                <button class="btn btn-outline-primary" type="submit">Search</button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th width="80px" class="text-center">No</th>
            <th width="100px" class="text-center">Logo</th>
            <th>Name</th>
            <th>Description</th>
            <th width="280px" class="text-center">Action</th>
        </tr>
        @foreach ($brands as $brand)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center align-middle">
                @if($brand->logo)
                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="max-height: 50px; max-width: 100%;">
                @else
                    <span class="text-muted">No logo</span>
                @endif
            </td>
            <td>{{ $brand->name }}</td>
            <td>{{ Str::limit($brand->description, 100) }}</td>
            <td class="text-center">
                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST">
                    <a class="btn btn-info btn-sm" href="{{ route('admin.brands.show', $brand->id) }}">Show</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.brands.edit', $brand->id) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this brand?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

    <div class="d-flex justify-content-center">
        {!! $brands->links() !!}
    </div>
</div>
@endsection