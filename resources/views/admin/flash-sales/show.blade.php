@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Flash Sale Details</h1>
        <div>
            <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Flash Sale
            </a>
            <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Flash Sale Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="font-weight-bold">Name:</label>
                        <p>{{ $flashSale->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Description:</label>
                        <p>{{ $flashSale->description ?? 'No description provided.' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Status:</label>
                        <p>
                            @switch($flashSale->status)
                                @case('active')
                                    <span class="badge bg-success">Active</span>
                                    @break
                                @case('ended')
                                    <span class="badge bg-danger">Ended</span>
                                    @break
                                @case('upcoming')
                                    <span class="badge bg-warning text-dark">Upcoming</span>
                                    @break
                                @case('disabled')
                                    <span class="badge bg-secondary">Disabled</span>
                                    @break
                            @endswitch
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Start Time:</label>
                        <p>{{ $flashSale->start_time->format('Y-m-d H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">End Time:</label>
                        <p>{{ $flashSale->end_time->format('Y-m-d H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Time Remaining:</label>
                        <p>{{ $flashSale->time_remaining ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Is Active Setting:</label>
                        <p>
                            @if($flashSale->is_active)
                                <span class="badge bg-success">Enabled</span>
                            @else
                                <span class="badge bg-secondary">Disabled</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Time Window:</label>
                        <p>
                            @if($flashSale->hasStarted() && !$flashSale->hasEnded())
                                <span class="badge bg-success">In Progress</span>
                            @elseif($flashSale->hasEnded())
                                <span class="badge bg-danger">Ended</span>
                            @else
                                <span class="badge bg-warning text-dark">Not Started Yet</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Products in Flash Sale</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Original Price</th>
                                    <th>Discount Price</th>
                                    <th>Discount %</th>
                                    <th>Quantity Limit</th>
                                    <th>Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($flashSale->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>${{ number_format($product->pivot->discount_price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $product->pivot->discount_percentage }}%</span>
                                        </td>
                                        <td>{{ $product->pivot->quantity_limit ?? 'No limit' }}</td>
                                        <td>{{ $product->pivot->sold_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No products in this flash sale.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 