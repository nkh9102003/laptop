@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">{{ __('messages.all_products') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Filters</h5>
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Search -->
                        <div class="mb-4">
                            <label for="search" class="form-label fw-medium">Search</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control" placeholder="Search products..." value="{{ request()->input('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-4">
                            <label for="brand" class="form-label fw-medium">Brand</label>
                            <select name="brand" id="brand" class="form-select">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request()->input('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Price Range</label>
                            <div class="d-flex align-items-center">
                                <input type="number" name="min_price" class="form-control me-2" placeholder="Min" value="{{ request()->input('min_price') }}">
                                <span class="text-muted">-</span>
                                <input type="number" name="max_price" class="form-control ms-2" placeholder="Max" value="{{ request()->input('max_price') }}">
                            </div>
                        </div>

                        <!-- Sort By -->
                        <div class="mb-4">
                            <label for="sort" class="form-label fw-medium">{{ __('messages.sort_by') }}</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="newest" {{ request()->input('sort') == 'newest' ? 'selected' : '' }}>{{ __('messages.newest') }}</option>
                                <option value="price_low" {{ request()->input('sort') == 'price_low' ? 'selected' : '' }}>{{ __('messages.price_low_high') }}</option>
                                <option value="price_high" {{ request()->input('sort') == 'price_high' ? 'selected' : '' }}>{{ __('messages.price_high_low') }}</option>
                                <option value="most_sold" {{ request()->input('sort') == 'most_sold' ? 'selected' : '' }}>{{ __('messages.most_sold') }}</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">Apply Filters</button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset Filters</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            @if($products->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No products found matching your criteria. Try adjusting your filters.
                </div>
            @else
                <div class="row g-4">
                    @foreach ($products as $product)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm product-card">
                            <div class="position-relative product-image-container">
                                <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                                <div class="overlay">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-light">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </a>
                                </div>
                                @if(isset($product->discount_percentage) && $product->discount_percentage > 0)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-3">
                                    -{{ $product->discount_percentage }}%
                                </span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-light text-dark">{{ $product->brand->name }}</span>
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <span class="ms-1">4.5</span>
                                    </div>
                                </div>
                                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                                    <h5 class="card-title text-dark">{{ $product->name }}</h5>
                                </a>
                                <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 50) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <h6 class="mb-0 fw-bold">${{ number_format($product->price, 2) }}</h6>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
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