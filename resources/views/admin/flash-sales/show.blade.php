@extends('layouts.admin')

@section('styles')
<style>
    .flash-sale-header {
        background: linear-gradient(to right, #e53935, #ff6f61);
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .status-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    
    .status-card:hover {
        transform: translateY(-5px);
    }
    
    .status-active {
        border-left-color: #28a745;
    }
    
    .status-upcoming {
        border-left-color: #ffc107;
    }
    
    .status-ended {
        border-left-color: #dc3545;
    }
    
    .status-disabled {
        border-left-color: #6c757d;
    }
    
    .timer-display {
        font-size: 1.75rem;
        font-weight: bold;
        margin-top: 0.5rem;
        letter-spacing: 1px;
    }
    
    .product-list {
        max-height: 600px;
        overflow-y: auto;
    }
    
    .progress-container {
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    
    .metric-card {
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        color: white;
        transition: transform 0.2s;
    }
    
    .metric-card:hover {
        transform: translateY(-5px);
    }
    
    .metric-card .value {
        font-size: 1.75rem;
        font-weight: bold;
    }
    
    .metric-card .label {
        opacity: 0.8;
        font-size: 0.875rem;
    }
    
    .discount-badge {
        font-size: 0.875rem;
        padding: 0.35rem 0.65rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="flash-sale-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $flashSale->name }}</h1>
            <p class="mb-0 mt-2 opacity-75">
                <i class="far fa-calendar-alt me-1"></i> 
                {{ $flashSale->start_time->format('M d, Y - H:i') }} to {{ $flashSale->end_time->format('M d, Y - H:i') }}
            </p>
        </div>
        <div>
            <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" class="btn btn-light">
                <i class="fas fa-edit"></i> Edit Flash Sale
            </a>
            <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-outline-light ms-2">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            @php
                $statusClass = 'status-disabled';
                $bgColor = 'bg-secondary';
                
                if ($flashSale->status === 'active') {
                    $statusClass = 'status-active';
                    $bgColor = 'bg-success';
                } elseif ($flashSale->status === 'upcoming') {
                    $statusClass = 'status-upcoming';
                    $bgColor = 'bg-warning';
                } elseif ($flashSale->status === 'ended') {
                    $statusClass = 'status-ended';
                    $bgColor = 'bg-danger';
                }
            @endphp
            
            <div class="card shadow mb-4 status-card {{ $statusClass }}">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <span class="badge {{ $bgColor }} px-3 py-2 fs-6">
                            @switch($flashSale->status)
                                @case('active')
                                    <i class="fas fa-bolt me-1"></i> Active
                                    @break
                                @case('ended')
                                    <i class="fas fa-calendar-times me-1"></i> Ended
                                    @break
                                @case('upcoming')
                                    <i class="fas fa-calendar-alt me-1"></i> Upcoming
                                    @break
                                @case('disabled')
                                    <i class="fas fa-ban me-1"></i> Disabled
                                    @break
                            @endswitch
                        </span>
                        
                        @if($flashSale->is_active) 
                            <small class="d-block mt-2 text-muted">
                                <i class="fas fa-toggle-on me-1"></i> Enabled in settings
                            </small>
                        @else
                            <small class="d-block mt-2 text-muted">
                                <i class="fas fa-toggle-off me-1"></i> Disabled in settings
                            </small>
                        @endif
                    </div>
                    
                    <h5 class="mt-4 mb-3">Time Status</h5>
                    
                    @if($flashSale->status === 'active')
                        <div class="timer-display text-success">
                            {{ $flashSale->time_remaining }}
                        </div>
                        <p class="text-muted mb-0">remaining until end</p>
                    @elseif($flashSale->status === 'upcoming')
                        <div class="timer-display text-warning">
                            {{ now()->diffForHumans($flashSale->start_time, ['parts' => 2]) }}
                        </div>
                        <p class="text-muted mb-0">until flash sale starts</p>
                    @elseif($flashSale->status === 'ended')
                        <div class="timer-display text-danger">
                            {{ $flashSale->end_time->diffForHumans(['parts' => 1]) }}
                        </div>
                        <p class="text-muted mb-0">since flash sale ended</p>
                    @else
                        <div class="timer-display text-secondary">
                            Disabled
                        </div>
                        <p class="text-muted mb-0">flash sale is not active</p>
                    @endif
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <div class="metric-card bg-primary">
                        <div class="value">{{ $flashSale->products->count() }}</div>
                        <div class="label">Products</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="metric-card bg-info">
                        <div class="value">
                            {{ $flashSale->products->sum(function($product) {
                                return $product->pivot->sold_count;
                            }) }}
                        </div>
                        <div class="label">Items Sold</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="metric-card bg-success">
                        <div class="value">
                            ${{ number_format($flashSale->products->sum(function($product) {
                                return $product->pivot->sold_count * $product->pivot->sale_price;
                            }), 2) }}
                        </div>
                        <div class="label">Revenue Generated</div>
                    </div>
                </div>
            </div>
            
            @if($flashSale->description)
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Description</h6>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $flashSale->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-9">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Products in Flash Sale</h6>
                    @if($flashSale->products->isNotEmpty())
                    <span class="badge bg-primary">{{ $flashSale->products->count() }} Products</span>
                    @endif
                </div>
                <div class="card-body product-list">
                    @if($flashSale->products->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Original Price</th>
                                        <th>Sale Price</th>
                                        <th>Discount</th>
                                        <th>Limit</th>
                                        <th>Sales Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($flashSale->products as $product)
                                        @php
                                            $discountPct = $product->pivot->discount_percentage;
                                            $discountClass = 'bg-info';
                                            
                                            if ($discountPct >= 50) {
                                                $discountClass = 'bg-danger';
                                            } elseif ($discountPct >= 25) {
                                                $discountClass = 'bg-warning text-dark';
                                            } elseif ($discountPct >= 10) {
                                                $discountClass = 'bg-info';
                                            } else {
                                                $discountClass = 'bg-secondary';
                                            }
                                            
                                            $soldPercentage = ($product->pivot->max_quantity > 0) 
                                                ? min(100, ($product->pivot->sold_count / $product->pivot->max_quantity) * 100)
                                                : 0;
                                                
                                            $progressClass = 'bg-info';
                                            if ($soldPercentage >= 80) {
                                                $progressClass = 'bg-danger';
                                            } elseif ($soldPercentage >= 50) {
                                                $progressClass = 'bg-warning';
                                            } elseif ($soldPercentage >= 25) {
                                                $progressClass = 'bg-success';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    @if($product->image)
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-laptop text-muted"></i>
                                                    </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $product->name }}</strong>
                                                        @if($product->brand)
                                                        <small class="d-block text-muted">{{ $product->brand->name }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">${{ number_format($product->price, 2) }}</td>
                                            <td class="align-middle text-danger">${{ number_format($product->pivot->sale_price, 2) }}</td>
                                            <td class="align-middle">
                                                <span class="badge {{ $discountClass }} discount-badge">
                                                    -{{ $product->pivot->discount_percentage }}%
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                @if($product->pivot->max_quantity > 0)
                                                    {{ $product->pivot->max_quantity }}
                                                @else
                                                    <span class="badge bg-secondary">No limit</span>
                                                @endif
                                            </td>
                                            <td class="align-middle" style="min-width: 150px">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <small>{{ $product->pivot->sold_count }} sold</small>
                                                    @if($product->pivot->max_quantity > 0)
                                                        <small>{{ $product->pivot->max_quantity - $product->pivot->sold_count }} left</small>
                                                    @endif
                                                </div>
                                                
                                                @if($product->pivot->max_quantity > 0)
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                                                             style="width: {{ $soldPercentage }}%;" 
                                                             aria-valuenow="{{ $soldPercentage }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100"></div>
                                                    </div>
                                                @else
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                             style="width: 100%;" 
                                                             aria-valuenow="100" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100"></div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> No products have been added to this flash sale.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 