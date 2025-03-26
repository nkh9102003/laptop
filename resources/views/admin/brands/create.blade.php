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
    
    .form-group label {
        font-weight: 600;
        color: #4e73df;
        margin-bottom: 0.5rem;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-submit {
        background: linear-gradient(to right, #1cc88a, #36b9cc);
        border: none;
        color: white;
        border-radius: 0.5rem;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-submit:hover {
        background: linear-gradient(to right, #17a673, #2c9faf);
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
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
    
    .form-control-file {
        padding: 1rem;
        background-color: #f8f9fc;
        border-radius: 0.5rem;
        border: 1px solid #e3e6f0;
    }
    
    .alert-danger {
        border-radius: 0.5rem;
        border-left: 5px solid #e74a3b;
    }
    
    .is-invalid {
        border-color: #e74a3b !important;
    }
    
    .invalid-feedback {
        color: #e74a3b;
        font-weight: 500;
    }
    
    .upload-placeholder {
        background-color: #f8f9fc;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        color: #b7b9cc;
        border: 2px dashed #e3e6f0;
        margin-bottom: 1.5rem;
    }
    
    .upload-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="brand-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Add New Brand</h1>
            <p class="mb-0 mt-2 opacity-75">
                <i class="fas fa-plus-circle me-1"></i> Create new brand with logo and information
            </p>
        </div>
        <a class="back-btn" href="{{ route('admin.brands.index') }}">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger mb-4">
        <div class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> Please fix the following errors:</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-lg-8">
                        <div class="brand-card">
                            <div class="brand-card-header">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-info-circle me-2 text-primary"></i> Brand Information
                                </h5>
                            </div>
                            <div class="brand-card-body">
                                <div class="form-group mb-4">
                                    <label for="name">Brand Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="Enter brand name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-2">
                                        <i class="fas fa-info-circle me-1"></i> The brand name will be visible to customers on product pages.
                                    </small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Brand Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" 
                                              rows="5" placeholder="Enter brand description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-2">
                                        <i class="fas fa-info-circle me-1"></i> A short description about the brand. This may be displayed on brand pages.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="brand-card">
                            <div class="brand-card-header">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-image me-2 text-primary"></i> Brand Logo
                                </h5>
                            </div>
                            <div class="brand-card-body">
                                <div class="upload-placeholder">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <p class="mb-0">Upload brand logo image</p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="logo">Select Logo</label>
                                    <input type="file" name="logo" class="form-control form-control-file @error('logo') is-invalid @enderror">
                                    <small class="form-text text-muted mt-2">
                                        <i class="fas fa-info-circle me-1"></i> Recommended size: 200x200 pixels. Max 2MB.
                                    </small>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-light me-3">
                        <i class="fas fa-times me-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-2"></i> Create Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection