@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Manage Orders</h2>
    <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search by user name" name="search" value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td>
                  <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PATCH')
                      <select name="status" onchange="this.form.submit()" class="form-select form-select-sm status-select text-white {{ $order->status == 'processing' ? 'bg-warning' : ($order->status == 'paid' ? 'bg-success' : 'bg-danger') }}">
                          <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                          <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                          <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                      </select>
                  </form>
                </td>
                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                        View Details
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $orders->appends(request()->query())->links() }}
</div>

@foreach($orders as $order)
<!-- Modal for each order -->
<div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">Order #{{ $order->id }} Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Customer Information</h6>
                <p><strong>Name:</strong> {{ $order->name }}</p>
                <p><strong>Contact:</strong> {{ $order->contact }}</p>
                <p><strong>Address:</strong> {{ $order->address }}</p>
                
                <h6 class="mt-4">Order Items</h6>
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
                              <a href="{{ route('admin.products.show', $item->product->id) }}" class="text-decoration-none">
                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                {{ $item->product->name }}
                              </a>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th>${{ number_format($order->total, 2) }}</th>
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

@endsection

@section('styles')
<style>
    .status-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }
    .status-select option {
        color: #333;
        background-color: white;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelects = document.querySelectorAll('select[name="status"]');
        statusSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    });
</script>
@endsection