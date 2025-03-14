@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Welcome to Our Laptop Store</h1>
    
    <h2 class="mb-4">Featured Products</h2>
    <div class="row">
        @foreach($featuredProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="position-relative product-image-container">
                    <img src="{{ $product->image }}" class="card-img-top product-image" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    <div class="overlay">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-light">View Details</a>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                        <h5 class="card-title text-dark">{{ $product->name }}</h5>
                    </a>
                    <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 50) }}</p>
                    <h6 class="mt-2 mb-3">${{ number_format($product->price, 2) }}</h6>
                </div>
                <div class="card-footer bg-white border-top-0">
                    @auth
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success w-100">
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .product-image-container {
        position: relative;
        overflow: hidden;
    }

    .product-image-container .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-image-container:hover .overlay {
        opacity: 1;
    }

    .product-image-container .overlay .btn {
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }

    .product-image-container:hover .overlay .btn {
        transform: translateY(0);
    }
</style>
@endsection