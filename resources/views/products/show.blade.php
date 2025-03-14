@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-5">
            <div class="product-image-container mb-4">
                <img src="{{ $product->image_url }}" class="img-fluid rounded" alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-md-7">
            <h2 class="mb-3">{{ $product->name }}</h2>
            <h5 class="text-muted mb-4">{{ $product->brand->name }}</h5>
            
            <div class="mb-4">
                <h3 class="text-primary">${{ number_format($product->price, 2) }}</h3>
            </div>
            
            <div class="mb-4">
                <p>{{ $product->description }}</p>
            </div>
            
            <div class="mb-4">
                <p class="mb-2">
                    <strong>Availability:</strong> 
                    @if($product->stock > 0)
                        <span class="text-success">In Stock ({{ $product->stock }} available)</span>
                    @else
                        <span class="text-danger">Out of Stock</span>
                    @endif
                </p>
            </div>
            
            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="quantity" class="col-form-label">Quantity:</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </form>
            @endif
            
            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Products
                </a>
            </div>
        </div>
    </div>
</div>
@endsection