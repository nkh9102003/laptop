@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('flash-sales.index') }}">Flash Sales</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $flashSale->name }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-danger text-white py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ $flashSale->name }}</h1>
                    <div class="mt-2 d-flex align-items-center">
                        <div class="flash-sale-timer me-3" data-end="{{ $flashSale->end_time }}">
                            <span class="badge bg-light text-danger">Ends in: <span id="flash-timer">00:00:00</span></span>
                        </div>
                        <small class="text-light">
                            <i class="far fa-calendar-alt me-1"></i> 
                            {{ $flashSale->start_time->format('M d, Y') }} - {{ $flashSale->end_time->format('M d, Y') }}
                        </small>
                    </div>
                </div>
                <a href="{{ route('flash-sales.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to Flash Sales
                </a>
            </div>
        </div>
        
        @if($flashSale->description)
        <div class="card-body border-bottom pb-4">
            <h5 class="card-title">About this Flash Sale</h5>
            <p class="card-text">{{ $flashSale->description }}</p>
        </div>
        @endif
    </div>

    <div class="row g-4">
        @forelse($flashSale->products as $product)
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm product-card flash-sale-card">
                <div class="position-relative product-image-container">
                    <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    <div class="overlay">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-light">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                    </div>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-3">
                        -{{ number_format((($product->price - $product->pivot->sale_price) / $product->price) * 100, 0) }}%
                    </span>
                </div>
                <div class="card-body d-flex flex-column p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-light text-dark">{{ $product->brand->name ?? 'Brand' }}</span>
                    </div>
                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                        <h5 class="card-title text-dark">{{ $product->name }}</h5>
                    </a>
                    <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            <span class="text-danger fw-bold fs-4">${{ number_format($product->pivot->sale_price, 2) }}</span>
                            <span class="text-muted text-decoration-line-through ms-2">${{ number_format($product->price, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="progress mt-3" style="height: 10px;">
                        @php 
                            $soldPercentage = ($product->pivot->max_quantity > 0) 
                                ? min(100, ($product->pivot->sold_count / $product->pivot->max_quantity) * 100)
                                : 0;
                        @endphp
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $soldPercentage }}%;" 
                             aria-valuenow="{{ $soldPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">{{ $product->pivot->max_quantity - $product->pivot->sold_count }} items left</small>
                        <small class="text-muted">{{ $product->pivot->sold_count }} sold</small>
                    </div>
                    
                    <div class="mt-3">
                        @auth
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="flash_sale_id" value="{{ $flashSale->id }}">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-danger w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </a>
                        @endauth
                    </div>
                </div>
                @if($product->pivot->max_quantity > 0 && ($product->pivot->max_quantity - $product->pivot->sold_count) < 5)
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-warning text-dark">
                            Only {{ $product->pivot->max_quantity - $product->pivot->sold_count }} left!
                        </span>
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <h4 class="alert-heading">No Products Available</h4>
                <p>This flash sale has no available products or all items have been sold out.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

@include('partials.flash-sales.scripts')
@include('partials.flash-sales.styles')
@endsection