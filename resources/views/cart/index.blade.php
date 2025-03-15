@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>

    <h1 class="h2 mb-4">Your Shopping Cart</h1>

    @if($cart->items->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3 ps-4">Product</th>
                                        <th class="py-3">Price</th>
                                        <th class="py-3">Quantity</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3 pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr>
                                            <td class="py-3 ps-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">{{ $item->product->brand->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 align-middle">${{ number_format($item->product->price, 2) }}</td>
                                            <td class="py-3 align-middle">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center quantity-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="input-group input-group-sm" style="width: 120px;">
                                                        <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="decrementQuantity(this)">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control text-center quantity-input">
                                                        <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="incrementQuantity(this)">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-primary ms-2 update-btn">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="py-3 align-middle fw-bold">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                            <td class="py-3 align-middle pe-4">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-2"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span>${{ number_format($cart->items->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong class="text-primary">${{ number_format($cart->items->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}</strong>
                        </div>
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
                        </button>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">We Accept</h6>
                        <div class="d-flex gap-2 mb-3">
                            <i class="fab fa-cc-visa fa-2x text-muted"></i>
                            <i class="fab fa-cc-mastercard fa-2x text-muted"></i>
                            <i class="fab fa-cc-amex fa-2x text-muted"></i>
                            <i class="fab fa-cc-paypal fa-2x text-muted"></i>
                        </div>
                        <hr>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-truck text-primary me-2"></i>
                            <span>Free Shipping on orders over $99</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-undo text-primary me-2"></i>
                            <span>30-day Return Policy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-5x text-muted"></i>
            </div>
            <h3 class="mb-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any products to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i> Start Shopping
            </a>
        </div>
    @endif
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Complete Your Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contact" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="contact" name="contact" required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Delivery Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="col-12">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                                        <label class="form-check-label w-100" for="cod">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-money-bill-wave fa-2x me-3 text-success"></i>
                                                <div>
                                                    <strong>Cash on Delivery</strong>
                                                    <div class="text-muted small">Pay when you receive your order</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="online" value="online">
                                        <label class="form-check-label w-100" for="online">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-credit-card fa-2x me-3 text-primary"></i>
                                                <div>
                                                    <strong>Online Payment</strong>
                                                    <div class="text-muted small">Credit/Debit card, PayPal</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle me-2"></i> Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function incrementQuantity(button) {
        const input = button.parentNode.querySelector('.quantity-input');
        const currentValue = parseInt(input.value);
        input.value = currentValue + 1;
    }
    
    function decrementQuantity(button) {
        const input = button.parentNode.querySelector('.quantity-input');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }
</script>
@endsection