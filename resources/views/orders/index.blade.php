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
                                Details
                            </button>
                            
                            @if($order->status === 'processing')
                                @if($order->payment_method === 'online')
                                    <a href="{{ route('payment.process', $order->id) }}" class="btn btn-sm btn-primary">
                                        Pay Now
                                    </a>
                                @endif
                                
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $orders->links() }}
        
        <!-- Order Detail Modals -->
        @foreach($orders as $order)
            <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">Order #{{ $order->id }} Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                                    <p><strong>Payment Method:</strong> {{ $order->payment_method === 'COD' ? 'Cash on Delivery' : 'Online Payment' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> {{ $order->name }}</p>
                                    <p><strong>Contact:</strong> {{ $order->contact }}</p>
                                    <p><strong>Address:</strong> {{ $order->address }}</p>
                                </div>
                            </div>
                            
                            <h6 class="mb-3">Order Items</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                {{ $item->product->name }}
                                                @if($item->flash_sale_id)
                                                    <span class="badge bg-danger ms-2">
                                                        Flash Sale
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->hasDiscount())
                                                    <span class="text-danger">${{ number_format($item->price, 2) }}</span>
                                                    <br>
                                                    <small class="text-decoration-line-through text-muted">
                                                        ${{ number_format($item->original_price, 2) }}
                                                    </small>
                                                    <small class="badge bg-danger ms-2">
                                                        -{{ $item->discount_percentage }}%
                                                    </small>
                                                @else
                                                    ${{ number_format($item->price, 2) }}
                                                @endif
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @php
                                        $hasDiscounts = $order->items->contains(function($item) {
                                            return $item->hasDiscount();
                                        });
                                        
                                        if ($hasDiscounts) {
                                            $originalTotal = $order->items->sum(function($item) {
                                                return ($item->original_price ?? $item->price) * $item->quantity;
                                            });
                                            $savedAmount = $originalTotal - $order->total;
                                        }
                                    @endphp
                                    
                                    @if(isset($savedAmount) && $savedAmount > 0)
                                    <tr>
                                        <th colspan="3" class="text-end text-muted">
                                            <small>Original Total:</small>
                                        </th>
                                        <td class="text-end text-muted">
                                            <small>${{ number_format($originalTotal, 2) }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end text-success">
                                            <small>You Saved:</small>
                                        </th>
                                        <td class="text-end text-success">
                                            <small>-${{ number_format($savedAmount, 2) }}</small>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end">${{ number_format($order->total, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            You don't have any orders yet. <a href="{{ route('products.index') }}">Start shopping</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // You can add any additional JavaScript for the modal here if needed
</script>
@endsection