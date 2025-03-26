@extends('layouts.admin')

@section('styles')
<style>
    .brand-header {
        background: linear-gradient(to right, #4a6bdf, #6a5acd);
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .search-container {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .search-container .form-control {
        border-radius: 0.5rem 0 0 0.5rem;
        border-right: none;
        box-shadow: none;
        padding: 0.6rem 1rem;
    }
    
    .search-container .btn-search {
        border-radius: 0 0.5rem 0.5rem 0;
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        color: white;
        border: none;
    }
    
    .search-container .btn-search:hover {
        background: linear-gradient(to right, #3a5fc7, #5849b1);
    }
    
    .search-container .btn-reset {
        border-radius: 0.5rem;
        color: #5a5c69;
        background-color: #f8f9fc;
        border: 1px solid #e3e6f0;
        transition: all 0.2s;
    }
    
    .search-container .btn-reset:hover {
        background-color: #eaecf4;
    }
    
    .create-btn {
        background: linear-gradient(to right, #1cc88a, #20c997);
        border: none;
        color: white;
        border-radius: 0.5rem;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s;
    }
    
    .create-btn:hover {
        background: linear-gradient(to right, #169d69, #1aa179);
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .brand-table {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
    }
    
    .brand-table thead {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        color: white;
    }
    
    .brand-table th {
        font-weight: 600;
        border: none;
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    
    .brand-table tbody tr {
        transition: all 0.2s;
    }
    
    .brand-table tbody tr:hover {
        background-color: #f8f9fc;
    }
    
    .brand-table td {
        vertical-align: middle;
        padding: 0.75rem;
        border-color: #e3e6f0;
    }
    
    .logo-container {
        width: 60px;
        height: 60px;
        border-radius: 0.5rem;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fc;
        border: 1px solid #e3e6f0;
        margin: 0 auto;
    }
    
    .logo-container img {
        max-height: 50px;
        max-width: 50px;
        object-fit: contain;
    }
    
    .no-logo {
        color: #b7b9cc;
        font-size: 0.875rem;
    }
    
    .brand-actions form {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .btn-view {
        background: linear-gradient(to right, #36b9cc, #4cc0f0);
        border: none;
        color: white;
    }
    
    .btn-view:hover {
        background: linear-gradient(to right, #2c9faf, #3ca8d9);
        color: white;
    }
    
    .btn-edit {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        border: none;
        color: white;
    }
    
    .btn-edit:hover {
        background: linear-gradient(to right, #3a5fc7, #5849b1);
        color: white;
    }
    
    .btn-delete {
        background: linear-gradient(to right, #e74a3b, #ff5c52);
        border: none;
        color: white;
    }
    
    .btn-delete:hover {
        background: linear-gradient(to right, #d52a1a, #e53935);
        color: white;
    }
    
    .brand-description {
        color: #5a5c69;
        font-size: 0.875rem;
    }
    
    .brand-name {
        font-weight: 600;
        color: #4e73df;
    }
    
    .pagination {
        margin-top: 1.5rem;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        border-color: #4e73df;
    }
    
    .alert-success {
        border-left: 4px solid #1cc88a;
        background-color: #eafaf1;
        color: #155724;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
</style>
@endsection
   
@section('content')
<div class="container-fluid">
    <div class="brand-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Brands Management</h1>
            <p class="mb-0 mt-2 opacity-75">
                <i class="fas fa-tag me-1"></i> Manage your product brands
            </p>
        </div>
        <a class="create-btn" href="{{ route('admin.brands.create') }}">
            <i class="fas fa-plus me-2"></i> Create New Brand
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i> {{ $message }}
        </div>
    @endif

    <div class="search-container">
        <form action="{{ route('admin.brands.index') }}" method="GET">
            <div class="row">
                <div class="col-md-8 col-lg-6">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search brands..." 
                               value="{{ request()->input('search') }}">
                        <button class="btn btn-search px-4" type="submit">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2 mt-2 mt-md-0">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-reset w-100">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="card brand-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="70px" class="text-center">#</th>
                            <th width="100px" class="text-center">Logo</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="200px" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $brand)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                <div class="logo-container">
                                    @if($brand->logo)
                                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}">
                                    @else
                                        <span class="no-logo">
                                            <i class="fas fa-image"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="brand-name">{{ $brand->name }}</div>
                            </td>
                            <td>
                                <div class="brand-description">{{ Str::limit($brand->description, 100) }}</div>
                            </td>
                            <td class="brand-actions">
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST">
                                    <a class="btn btn-sm btn-view" href="{{ route('admin.brands.show', $brand->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-edit" href="{{ route('admin.brands.edit', $brand->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete" 
                                            onclick="return confirm('Are you sure you want to delete this brand?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-tag fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No brands found</h5>
                                    <p class="text-muted">Create a new brand to get started</p>
                                    <a class="btn btn-sm create-btn mt-2" href="{{ route('admin.brands.create') }}">
                                        <i class="fas fa-plus me-2"></i> Create New Brand
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {!! $brands->links() !!}
    </div>
</div>
@endsection