@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 display-5 fw-bold">{{ __('Flash Sales') }}</h1>
    
    @if($activeFlashSales->count() > 0)
        @foreach($activeFlashSales as $flashSale)
        <div class="card mb-5 border-0 shadow-sm">
            <div class="card-header bg-danger text-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-0">{{ $flashSale->name }}</h2>
                    <div class="flash-sale-timer mt-2" data-end="{{ $flashSale->end_time }}">
                        <span class="badge bg-light text-danger">Ends in: <span class="timer-display">00:00:00</span></span>
                    </div>
                </div>
                <a href="{{ route('flash-sales.show', $flashSale) }}" class="btn btn-light text-danger">
                    {{ __('View All') }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-4">
                @if($flashSale->description)
                <div class="mb-4">
                    <p>{{ $flashSale->description }}</p>
                </div>
                @endif
                
                <div class="row g-4">
                    @foreach($flashSale->products->take(6) as $product)
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
                                        <input type="hidden" name="flash_sale_id" value="{{ $flashSale->id }}">
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
                                        $product = isset($product) && $product ? $product : null;
                                        $soldPercentage = $product && isset($product->pivot) && $product->pivot && $product->pivot->max_quantity > 0 
                                            ? min(100, ($product->pivot->sold_count / $product->pivot->max_quantity) * 100)
                                            : 0;
                                    @endphp
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $soldPercentage }}%;" 
                                         aria-valuenow="{{ $soldPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted mt-1">
                                    {{ $product && isset($product->pivot) && $product->pivot && $product->pivot->max_quantity > 0 
                                       ? $product->pivot->max_quantity - $product->pivot->sold_count 
                                       : 'Unlimited' }} items left
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info">
            <h4 class="alert-heading">{{ __('No Flash Sales Available') }}</h4>
            <p>{{ __('There are no active flash sales at the moment. Please check back later!') }}</p>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flash sale countdown timers
        document.querySelectorAll('.flash-sale-timer').forEach(timerEl => {
            const endTime = new Date(timerEl.dataset.end).getTime();
            const timerDisplay = timerEl.querySelector('.timer-display');
            
            const updateTimer = function() {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance <= 0) {
                    timerDisplay.textContent = 'Expired';
                    return;
                }
                
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                timerDisplay.textContent = 
                    (hours < 10 ? '0' + hours : hours) + ':' +
                    (minutes < 10 ? '0' + minutes : minutes) + ':' +
                    (seconds < 10 ? '0' + seconds : seconds);
            };
            
            updateTimer();
            setInterval(updateTimer, 1000);
        });
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
    
    .product-image-container {
        position: relative;
        overflow: hidden;
        height: 150px;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-image-container:hover .overlay {
        opacity: 1;
    }
    
    .product-image-container:hover .product-image {
        transform: scale(1.1);
    }
</style>
@endsection