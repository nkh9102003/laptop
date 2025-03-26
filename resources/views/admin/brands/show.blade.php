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
    
    .brand-card {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        border: none;
    }
    
    .brand-card-header {
        background-color: #f8f9fc;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .brand-card-body {
        padding: 1.5rem;
    }
    
    .brand-logo-container {
        background-color: #f8f9fc;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 1.5rem;
        border: 1px solid #e3e6f0;
    }
    
    .brand-logo {
        max-height: 150px;
        max-width: 100%;
    }
    
    .brand-info-label {
        color: #4e73df;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .brand-info-value {
        font-size: 1.1rem;
        margin-bottom: 1.25rem;
    }
    
    .no-logo-placeholder {
        background-color: #f8f9fc;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        color: #b7b9cc;
    }
    
    .no-logo-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    
    .back-btn {
        background: linear-gradient(to right, #858796, #6c757d);
        border: none;
        color: white;
        border-radius: 0.5rem;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .back-btn:hover {
        background: linear-gradient(to right, #717380, #5a6268);
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        color: white;
    }
    
    .edit-btn {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        border: none;
        color: white;
        border-radius: 0.5rem;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .edit-btn:hover {
        background: linear-gradient(to right, #3a5fc7, #5849b1);
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        color: white;
    }
</style>
@endsection
  
@section('content')
<div class="container-fluid">
    <div class="brand-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">{{ $brand->name }}</h1>
            <p class="mb-0 mt-2 opacity-75">
                <i class="fas fa-tag me-1"></i> Brand Details
            </p>
        </div>
        <div class="d-flex gap-2">
            <a class="back-btn" href="{{ route('admin.brands.index') }}">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
            <a class="edit-btn" href="{{ route('admin.brands.edit', $brand->id) }}">
                <i class="fas fa-edit me-2"></i> Edit Brand
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="brand-card">
                <div class="brand-card-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-image me-2 text-primary"></i> Brand Logo
                    </h5>
                </div>
                <div class="brand-card-body">
                    @if($brand->logo)
                        <div class="brand-logo-container">
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="brand-logo">
                        </div>
                    @else
                        <div class="no-logo-placeholder">
                            <div class="no-logo-icon">
                                <i class="fas fa-image"></i>
                            </div>
                            <p class="mb-0">No logo uploaded for this brand</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="brand-card">
                <div class="brand-card-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2 text-primary"></i> Brand Information
                    </h5>
                </div>
                <div class="brand-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="brand-info-label">Brand Name</div>
                            <div class="brand-info-value">{{ $brand->name }}</div>
                            
                            <div class="brand-info-label">Created Date</div>
                            <div class="brand-info-value">{{ $brand->created_at->format('F d, Y') }}</div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="brand-info-label">Last Updated</div>
                            <div class="brand-info-value">{{ $brand->updated_at->format('F d, Y') }}</div>
                            
                            @if($brand->products_count ?? null)
                            <div class="brand-info-label">Products</div>
                            <div class="brand-info-value">
                                <span class="badge bg-primary px-3 py-2">{{ $brand->products_count }} Products</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="brand-info-label">Description</div>
                    <div class="brand-info-value">
                        @if($brand->description)
                            {{ $brand->description }}
                        @else
                            <span class="text-muted">No description provided.</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Additional card for Products by this Brand - future enhancement -->
            <div class="brand-card">
                <div class="brand-card-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-laptop me-2 text-primary"></i> Actions
                    </h5>
                </div>
                <div class="brand-card-body">
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="{{ route('admin.products.index', ['brand' => $brand->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i> View All Products by this Brand
                        </a>
                        
                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" 
                                    onclick="return confirm('Are you sure you want to delete this brand?')">
                                <i class="fas fa-trash me-2"></i> Delete Brand
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection