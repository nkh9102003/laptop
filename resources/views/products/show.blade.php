@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            @if ($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm mb-4">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                    <p class="text-muted">No image available</p>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h1 class="mb-3">{{ $product->name }}</h1>
                <p class="text-muted mb-4">{{ $product->description }}</p>
                <h3 class="text-success mb-4">${{ number_format($product->price, 2) }}</h3>
                <p><strong>Brand:</strong> {{ $product->brand->name }}</p>
                <p><strong>In Stock:</strong> {{ $product->stock }}</p>

                @auth
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                    </form>
                @else
                    <p class="mt-4">Please <a href="{{ route('login') }}" class="fw-bold">login</a> to add this item to your cart.</p>
                @endauth

                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3">
                    <i class="fas fa-arrow-left me-2"></i> Back to Products
                </a>
            </div>
        </div>
    </div>
</div>
@endsection