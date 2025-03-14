@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Our Products</h2>
    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request()->input('search') }}">
            </div>
            <div class="col-md-4">
                <select name="brand" class="form-control">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request()->input('brand') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="row">
        @foreach ($products as $product)
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
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {!! $products->appends(request()->query())->links() !!}
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