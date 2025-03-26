@extends('layouts.admin')

@section('styles')
<style>
    .order-header {
        background: linear-gradient(to right, #4a6bdf, #6a5acd);
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .search-container {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .search-container .form-control {
        border-radius: 0.5rem 0 0 0.5rem;
        border-right: none;
        box-shadow: none;
        padding: 0.6rem 1rem;
    }
    
    .search-container .btn-search {
        border-radius: 0 0.5rem 0.5rem 0;
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        color: white;
        border: none;
    }
    
    .search-container .btn-search:hover {
        background: linear-gradient(to right, #3a5fc7, #5849b1);
    }
    
    .search-container .btn-reset {
        border-radius: 0.5rem;
        color: #5a5c69;
        background-color: #f8f9fc;
        border: 1px solid #e3e6f0;
        transition: all 0.2s;
    }
    
    .search-container .btn-reset:hover {
        background-color: #eaecf4;
    }
    
    .order-table {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
    }
    
    .order-table thead {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        color: white;
    }
    
    .order-table th {
        font-weight: 600;
        border: none;
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    
    .order-table tbody tr {
        transition: all 0.2s;
    }
    
    .order-table tbody tr:hover {
        background-color: #f8f9fc;
    }
    
    .order-table td {
        vertical-align: middle;
        padding: 0.75rem;
        border-color: #e3e6f0;
    }
    
    .order-id {
        font-weight: 600;
        color: #4e73df;
    }
    
    .order-total {
        font-weight: 600;
    }
    
    .status-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        border: none;
        border-radius: 0.5rem;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .bg-processing {
        background: linear-gradient(to right, #f6c23e, #f9d974);
    }
    
    .bg-paid {
        background: linear-gradient(to right, #1cc88a, #20c997);
    }
    
    .bg-cancelled {
        background: linear-gradient(to right, #e74a3b, #ff5c52);
    }
    
    .status-select option {
        color: #333;
        background-color: white;
    }
    
    .btn-view-details {
        background: linear-gradient(to right, #36b9cc, #4cc0f0);
        border: none;
        color: white;
        border-radius: 0.5rem;
        transition: all 0.3s;
    }
    
    .btn-view-details:hover {
        background: linear-gradient(to right, #2c9faf, #3ca8d9);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .pagination {
        margin-top: 1.5rem;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        border-color: #4e73df;
    }
    
    /* Modal Customization */
    .modal-header {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        color: white;
        border-bottom: none;
    }
    
    .modal-title {
        font-weight: 600;
    }
    
    .modal-content {
        border: none;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .section-heading {
        color: #4e73df;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .customer-info {
        background-color: #f8f9fc;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.25rem;
        border: 1px solid #e3e6f0;
    }
    
    .order-item {
        transition: all 0.2s;
    }
    
    .order-item:hover {
        background-color: #f8f9fc;
    }
    
    .flash-sale-badge {
        background: linear-gradient(to right, #e74a3b, #ff5c52);
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }
    
    .order-summary {
        background-color: #f8f9fc;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-top: 1.5rem;
    }
    
    .modal-footer {
        border-top: none;
    }
    
    .btn-close-modal {
        background: linear-gradient(to right, #858796, #6c757d);
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s;
    }
    
    .btn-close-modal:hover {
        background: linear-gradient(to right, #717380, #5a6268);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="order-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Order Management</h1>
            <p class="mb-0 mt-2 opacity-75">
                <i class="fas fa-shopping-cart me-1"></i> Manage and track customer orders
            </p>
        </div>
    </div>

    <div class="search-container">
        <form action="{{ route('admin.orders.index') }}" method="GET">
            <div class="row">
                <div class="col-md-8 col-lg-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by user name" 
                               name="search" value="{{ request('search') }}">
                        <button class="btn btn-search px-4" type="submit">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2 mt-2 mt-md-0">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-reset w-100">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
        </div>
    </form>
    </div>

    <div class="card order-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>Order ID</th>
                            <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                            <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <span class="order-id">#{{ $order->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle me-2 text-muted"></i>
                                    {{ $order->user->name }}
                                </div>
                            </td>
                            <td>
                                <span class="order-total">${{ number_format($order->total, 2) }}</span>
                            </td>
                <td>
                  <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="status-select text-white fw-bold {{ 
                                                $order->status == 'processing' ? 'bg-processing' : 
                                                ($order->status == 'paid' ? 'bg-paid' : 'bg-cancelled') 
                                            }}">
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                            Processing
                                        </option>
                                        <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>
                                            Paid
                                        </option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
                      </select>
                  </form>
                </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                    {{ $order->created_at->format('M d, Y') }}
                                    <small class="text-muted ms-2">{{ $order->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-view-details" 
                                        data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                    <i class="fas fa-eye me-1"></i> View Details
                    </button>
                </td>
            </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No orders found</h5>
                                    <p class="text-muted">No orders match your search criteria</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
        </tbody>
    </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
    {{ $orders->appends(request()->query())->links() }}
    </div>
</div>

@foreach($orders as $order)
<!-- Modal for each order -->
<div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">
                    <i class="fas fa-shopping-cart me-2"></i> Order #{{ $order->id }} Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="section-heading">
                    <i class="fas fa-user me-2"></i> Customer Information
                </h6>
                <div class="customer-info">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-2">
                                <span class="text-muted">Name:</span>
                                <div class="fw-bold">{{ $order->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <span class="text-muted">Contact:</span>
                                <div class="fw-bold">{{ $order->contact }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <span class="text-muted">Status:</span>
                                <div>
                                    <span class="badge {{ 
                                        $order->status == 'processing' ? 'bg-warning' : 
                                        ($order->status == 'paid' ? 'bg-success' : 'bg-danger') 
                                    }} px-3 py-2">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-muted">Address:</span>
                        <div class="fw-bold">{{ $order->address }}</div>
                    </div>
                </div>
                
                <h6 class="section-heading">
                    <i class="fas fa-box-open me-2"></i> Order Items
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th>Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="order-item">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                             class="product-image me-3">
                                        <div>
                                            <a href="{{ route('admin.products.show', $item->product->id) }}" class="text-decoration-none fw-bold">
                                {{ $item->product->name }}
                              </a>
                                            @if($item->flash_sale_id)
                                                <span class="flash-sale-badge ms-2">
                                                    <i class="fas fa-bolt me-1"></i> Flash Sale
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end">
                                    @if($item->hasDiscount())
                                        <div class="text-danger fw-bold">${{ number_format($item->price, 2) }}</div>
                                        <div>
                                            <small class="text-decoration-line-through text-muted">
                                                ${{ number_format($item->original_price, 2) }}
                                            </small>
                                            <span class="badge bg-danger ms-1">-{{ $item->discount_percentage }}%</span>
                                        </div>
                                    @else
                                        <span class="fw-bold">${{ number_format($item->price, 2) }}</span>
                                    @endif
                            </td>
                                <td class="text-end fw-bold">${{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>

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
                
                <div class="order-summary">
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table table-sm">
                                @if(isset($savedAmount) && $savedAmount > 0)
                                <tr>
                                    <td class="text-muted border-0">Original Total:</td>
                                    <td class="text-end text-muted border-0">${{ number_format($originalTotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-success border-0">
                                        <i class="fas fa-bolt me-1"></i> Flash Sale Savings:
                                    </td>
                                    <td class="text-end text-success fw-bold border-0">
                                        -${{ number_format($savedAmount, 2) }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-bold fs-5 border-0">Total:</td>
                                    <td class="text-end fw-bold fs-5 border-0">${{ number_format($order->total, 2) }}</td>
                        </tr>
                </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

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