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
                    <div class="mt-2 d-flex align-items-center">
                        <div class="flash-sale-timer me-3" data-end="{{ $flashSale->end_time }}">
                            <span class="badge bg-light text-danger">Ends in: <span class="timer-display">00:00:00</span></span>
                        </div>
                        <small class="text-light">
                            <i class="far fa-calendar-alt me-1"></i> 
                            {{ $flashSale->start_time->format('M d, Y') }} - {{ $flashSale->end_time->format('M d, Y') }}
                        </small>
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
                        <div class="card h-100 border-0 shadow-sm product-card flash-sale-card flash-sale-small">
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

@include('partials.flash-sales.scripts')
@include('partials.flash-sales.styles')
@endsection