@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5 mb-5 bg-light rounded-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-5 fw-bold mb-3">{{ __('messages.hero_title') }}</h1>
                <p class="lead mb-4">{{ __('messages.hero_subtitle') }}</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        {{ __('messages.shop_now') }} <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="#featured-products" class="btn btn-outline-primary btn-lg">
                        {{ __('messages.view_featured') }}
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1611078489935-0cb964de46d6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1074&q=80" alt="Premium Laptops" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="mb-5">
    <div class="container">
        <h2 class="text-center mb-4">{{ __('messages.shop_by_category') }}</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm category-card">
                    <div class="card-body text-center p-4">
                        <div class="category-icon mb-3">
                            <i class="fas fa-laptop fa-3x text-primary"></i>
                        </div>
                        <h3 class="h5">{{ __('messages.business_laptops') }}</h3>
                        <p class="text-muted">{{ __('messages.business_laptops_desc') }}</p>
                        <a href="{{ route('products.index') }}?category=business" class="btn btn-outline-primary mt-2">{{ __('messages.explore') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm category-card">
                    <div class="card-body text-center p-4">
                        <div class="category-icon mb-3">
                            <i class="fas fa-gamepad fa-3x text-primary"></i>
                        </div>
                        <h3 class="h5">{{ __('messages.gaming_laptops') }}</h3>
                        <p class="text-muted">{{ __('messages.gaming_laptops_desc') }}</p>
                        <a href="{{ route('products.index') }}?category=gaming" class="btn btn-outline-primary mt-2">{{ __('messages.explore') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm category-card">
                    <div class="card-body text-center p-4">
                        <div class="category-icon mb-3">
                            <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                        </div>
                        <h3 class="h5">{{ __('messages.student_laptops') }}</h3>
                        <p class="text-muted">{{ __('messages.student_laptops_desc') }}</p>
                        <a href="{{ route('products.index') }}?category=student" class="btn btn-outline-primary mt-2">{{ __('messages.explore') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Flash Sale Section -->
@if(isset($activeFlashSale) && $activeFlashSale && $activeFlashSale->products->count() > 0)
<section class="mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 text-danger">{{ __('Flash Sale') }}</h2>
                <div class="flash-sale-timer mt-2" data-end="{{ $activeFlashSale->end_time }}">
                    <span class="badge bg-danger">Ends in: <span id="flash-timer">00:00:00</span></span>
                </div>
            </div>
            <a href="{{ route('flash-sales.index') }}" class="btn btn-outline-danger">{{ __('View All Deals') }} <i class="fas fa-bolt ms-1"></i></a>
        </div>
        
        <div class="row g-4">
            @foreach($activeFlashSale->products as $product)
            <div class="col-md-2">
                <div class="card h-100 border-0 shadow-sm product-card flash-sale-card">
                    <div class="position-relative product-image-container">
                        <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                        <div class="overlay">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                            -{{ number_format((($product->price - $product->pivot->sale_price) / $product->price) * 100, 0) }}%
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                            <h6 class="card-title text-dark">{{ Str::limit($product->name, 30) }}</h6>
                        </a>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <span class="text-danger fw-bold">${{ number_format($product->pivot->sale_price, 2) }}</span>
                                <small class="text-muted text-decoration-line-through d-block">${{ number_format($product->price, 2) }}</small>
                            </div>
                            @auth
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="flash_sale_id" value="{{ $activeFlashSale->id }}">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                            @endauth
                        </div>
                        <div class="progress mt-2" style="height: 10px;">
                            @php 
                                $soldPercentage = ($product->pivot->max_quantity > 0) 
                                    ? min(100, ($product->pivot->sold_count / $product->pivot->max_quantity) * 100)
                                    : 0;
                            @endphp
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $soldPercentage }}%;" 
                                 aria-valuenow="{{ $soldPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted mt-1">
                            {{ $product->pivot->max_quantity > 0 ? $product->pivot->max_quantity - $product->pivot->sold_count : 'Unlimited' }} items left
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flash sale countdown timer
        const flashSaleTimer = document.querySelector('.flash-sale-timer');
        if (flashSaleTimer) {
            const endTime = new Date(flashSaleTimer.dataset.end).getTime();
            
            const updateTimer = function() {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance <= 0) {
                    document.getElementById('flash-timer').textContent = 'Expired';
                    return;
                }
                
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('flash-timer').textContent = 
                    (hours < 10 ? '0' + hours : hours) + ':' +
                    (minutes < 10 ? '0' + minutes : minutes) + ':' +
                    (seconds < 10 ? '0' + seconds : seconds);
            };
            
            updateTimer();
            setInterval(updateTimer, 1000);
        }
    });
</script>

<style>
    .flash-sale-card {
        transition: transform 0.2s ease;
        border-radius: 0.5rem;
        border-left: 3px solid #dc3545 !important;
    }
    
    .flash-sale-card:hover {
        transform: translateY(-5px);
    }
</style>
@endif

<!-- Featured Products Section -->
<section id="featured-products" class="mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">{{ __('messages.featured_products') }}</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">{{ __('messages.view_all') }} <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative product-image-container">
                        <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                        <div class="overlay">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-light">
                                <i class="fas fa-eye me-1"></i> {{ __('messages.view_details') }}
                            </a>
                        </div>
                        @if($product->discount_percentage > 0)
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
                            @auth
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light rounded-3 mb-5">
    <div class="container">
        <h2 class="text-center mb-5">{{ __('messages.why_choose_us') }}</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-truck fa-3x text-primary"></i>
                    </div>
                    <h5>{{ __('messages.free_shipping') }}</h5>
                    <p class="text-muted">{{ __('messages.free_shipping_desc') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-undo fa-3x text-primary"></i>
                    </div>
                    <h5>{{ __('messages.easy_returns') }}</h5>
                    <p class="text-muted">{{ __('messages.easy_returns_desc') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-lock fa-3x text-primary"></i>
                    </div>
                    <h5>{{ __('messages.secure_payment') }}</h5>
                    <p class="text-muted">{{ __('messages.secure_payment_desc') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-headset fa-3x text-primary"></i>
                    </div>
                    <h5>{{ __('messages.support') }}</h5>
                    <p class="text-muted">{{ __('messages.support_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .category-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 0.75rem;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .category-icon {
        height: 80px;
        width: 80px;
        background-color: rgba(37, 99, 235, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .feature-icon {
        height: 70px;
        width: 70px;
        background-color: rgba(37, 99, 235, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .hero-section {
        background-image: linear-gradient(to right, rgba(249, 250, 251, 0.9), rgba(249, 250, 251, 0.7)), url('https://images.unsplash.com/photo-1517336714731-489689fd1ca4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1626&q=80');
        background-size: cover;
        background-position: center;
    }
</style>
@endsection