@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="product-image-container mb-4 position-relative">
                <img src="{{ $product->image_url }}" class="img-fluid rounded shadow-sm" alt="{{ $product->name }}">
                @if(isset($product->discount_percentage) && $product->discount_percentage > 0)
                <span class="badge bg-danger position-absolute top-0 start-0 m-3">
                    -{{ $product->discount_percentage }}%
                </span>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="product-details p-4 bg-white rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-light text-dark">{{ $product->brand->name }}</span>
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span class="ms-1 text-dark">(4.5)</span>
                    </div>
                </div>
                
                <h1 class="h2 mb-3">{{ $product->name }}</h1>
                
                <div class="mb-4">
                    <h3 class="text-primary fw-bold">${{ number_format($product->price, 2) }}</h3>
                </div>
                
                <div class="mb-4">
                    <p class="text-muted">{{ $product->description }}</p>
                </div>
                
                <div class="mb-4 d-flex align-items-center">
                    <span class="me-3"><strong>Availability:</strong></span>
                    @if($product->stock > 0)
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i> In Stock ({{ $product->stock }} available)
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger px-3 py-2">
                            <i class="fas fa-times-circle me-1"></i> Out of Stock
                        </span>
                    @endif
                </div>
                
                <hr class="my-4">
                
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
                
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <div class="feature d-flex align-items-center">
                        <i class="fas fa-truck text-primary me-2"></i>
                        <span>Free shipping on orders over $99</span>
                    </div>
                    <div class="feature d-flex align-items-center">
                        <i class="fas fa-undo text-primary me-2"></i>
                        <span>30-day return policy</span>
                    </div>
                    <div class="feature d-flex align-items-center">
                        <i class="fas fa-lock text-primary me-2"></i>
                        <span>Secure payment</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Specifications -->
    @if(!$product->specifications->isEmpty())
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Technical Specifications</h4>
                </div>
                <div class="card-body">
                    @php
                        $groupedSpecs = $product->getSpecificationsByCategory();
                    @endphp
                    
                    <div class="accordion" id="specificationsAccordion">
                        @foreach($groupedSpecs as $categoryId => $data)
                            <div class="accordion-item border-0 mb-3">
                                <h2 class="accordion-header" id="heading{{ $categoryId }}">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $categoryId }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $categoryId }}">
                                        <i class="fas fa-cogs me-2"></i> {{ $data['category']->display_name }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $categoryId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $categoryId }}" data-bs-parent="#specificationsAccordion">
                                    <div class="accordion-body bg-light rounded-bottom">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <tbody>
                                                    @foreach($data['specifications'] as $spec)
                                                    <tr>
                                                        <th class="bg-white" style="width: 40%">{{ $spec->specificationType->display_name }}</th>
                                                        <td class="bg-white">{{ $spec->formatted_value }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Related Products -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">You May Also Like</h3>
            <div class="row g-4">
                @for($i = 0; $i < 4; $i++)
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative product-image-container">
                            <img src="https://via.placeholder.com/300x200?text=Related+Product" class="card-img-top product-image" alt="Related Product">
                            <div class="overlay">
                                <a href="#" class="btn btn-light">
                                    <i class="fas fa-eye me-1"></i> View Details
                                </a>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-dark">Brand</span>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <span class="ms-1">4.5</span>
                                </div>
                            </div>
                            <a href="#" class="text-decoration-none">
                                <h5 class="card-title text-dark">Related Product {{ $i + 1 }}</h5>
                            </a>
                            <p class="card-text text-muted flex-grow-1">This is a related product that you might be interested in.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h6 class="mb-0 fw-bold">$999.99</h6>
                                <button class="btn btn-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>

@endsection