@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Your Cart</h2>
    @if($cart->items->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart->items as $item)
                    <tr>
                        <td>
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                            {{ $item->product->name }}
                        </td>
                        <td>${{ number_format($item->product->price, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width: 60px;">
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                            </form>
                        </td>
                        <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td colspan="2"><strong>${{ number_format($cart->items->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutModal">
            Proceed to Checkout
        </button>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact (Phone)</label>
                        <input type="number" class="form-control" id="contact" name="contact" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="COD">Cash on Delivery</option>
                            <option value="online">Online Payment</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // You can add any additional JavaScript for the modal here
</script>
@endsection