@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Your Orders</h2>
    @if($orders->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'processing' ? 'warning' : ($order->status === 'paid' ? 'success' : 'danger') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->payment_method === 'COD' ? 'Cash on Delivery' : 'Online Payment' }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                View Details
                            </button>
                            @if($order->status === 'processing')
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');">
                                        Cancel Order
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @else
        <p>You haven't placed any orders yet.</p>
    @endif
</div>

<!-- Order Details Modals -->
@foreach($orders as $order)
    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">Order #{{ $order->id }} Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    <p><strong>Payment Method:</strong> {{ $order->payment_method === 'COD' ? 'Cash on Delivery' : 'Online Payment' }}</p>
                    <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                    
                    <h6>Order Items:</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('products.show', $item->product->id) }}" class="text-decoration-none">
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            {{ $item->product->name }}
                                        </a>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('scripts')
<script>
    // You can add any additional JavaScript for the modal here if needed
</script>
@endsection