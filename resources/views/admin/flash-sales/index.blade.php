@extends('layouts.admin')

@section('styles')
<style>
    .flash-sale-header {
        background: linear-gradient(to right, #4a6bdf, #6a5acd);
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .create-btn {
        background: linear-gradient(to right, #1cc88a, #20c997);
        border: none;
        color: white;
        border-radius: 0.5rem;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s;
    }
    
    .create-btn:hover {
        background: linear-gradient(to right, #169d69, #1aa179);
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .flash-sale-table {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
    }
    
    .flash-sale-table thead {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        color: white;
    }
    
    .flash-sale-table th {
        font-weight: 600;
        border: none;
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    
    .flash-sale-table tbody tr {
        transition: all 0.2s;
    }
    
    .flash-sale-table tbody tr:hover {
        background-color: #f8f9fc;
    }
    
    .flash-sale-table td {
        vertical-align: middle;
        padding: 0.75rem;
        border-color: #e3e6f0;
    }
    
    .flash-sale-name {
        font-weight: 600;
        color: #4e73df;
    }
    
    .flash-sale-description {
        color: #6c757d;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .badge-inactive {
        background: linear-gradient(to right, #858796, #6c757d);
    }
    
    .badge-upcoming {
        background: linear-gradient(to right, #f6c23e, #f9d974);
        color: #533f03;
    }
    
    .badge-active {
        background: linear-gradient(to right, #1cc88a, #20c997);
    }
    
    .badge-ended {
        background: linear-gradient(to right, #e74a3b, #ff5c52);
    }
    
    .status-badge {
        font-weight: bold;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-view {
        background: linear-gradient(to right, #36b9cc, #4cc0f0);
        border: none;
        color: white;
    }
    
    .btn-view:hover {
        background: linear-gradient(to right, #2c9faf, #3ca8d9);
        color: white;
    }
    
    .btn-edit {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        border: none;
        color: white;
    }
    
    .btn-edit:hover {
        background: linear-gradient(to right, #3a5fc7, #5849b1);
        color: white;
    }
    
    .btn-delete {
        background: linear-gradient(to right, #e74a3b, #ff5c52);
        border: none;
        color: white;
    }
    
    .btn-delete:hover {
        background: linear-gradient(to right, #d52a1a, #e53935);
        color: white;
    }
    
    .time-badge {
        background-color: #f8f9fc;
        color: #5a5c69;
        padding: 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid #e3e6f0;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: fit-content;
    }
    
    .product-count {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
    }
    
    .pagination {
        margin-top: 1.5rem;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        border-color: #4e73df;
    }
    
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #d1d3e2;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="flash-sale-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Flash Sales Management</h1>
            <p class="mb-0 mt-2 opacity-75">
                <i class="fas fa-bolt me-1"></i> Create and manage limited-time promotional offers
            </p>
        </div>
        <a href="{{ route('admin.flash-sales.create') }}" class="create-btn">
            <i class="fas fa-plus me-2"></i> Create New Flash Sale
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card flash-sale-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Flash Sale</th>
                            <th>Schedule</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Products</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flashSales as $flashSale)
                            <tr>
                                <td>
                                    <div class="flash-sale-name">{{ $flashSale->name }}</div>
                                    @if($flashSale->description)
                                        <div class="flash-sale-description" title="{{ $flashSale->description }}">
                                            {{ $flashSale->description }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="time-badge mb-1">
                                        <i class="fas fa-calendar-day text-primary"></i>
                                        <span>{{ $flashSale->start_time->format('M d, Y') }}</span>
                                    </div>
                                    <div class="time-badge">
                                        <i class="fas fa-calendar-check text-danger"></i>
                                        <span>{{ $flashSale->end_time->format('M d, Y') }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if(!$flashSale->is_active)
                                        <span class="status-badge badge-inactive">Inactive</span>
                                    @elseif($flashSale->start_time > now())
                                        <span class="status-badge badge-upcoming">Upcoming</span>
                                    @elseif($flashSale->end_time < now())
                                        <span class="status-badge badge-ended">Ended</span>
                                    @else
                                        <span class="status-badge badge-active">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="product-count">{{ $flashSale->products->count() }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.flash-sales.show', $flashSale) }}" 
                                           class="btn btn-sm btn-view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" 
                                           class="btn btn-sm btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.flash-sales.destroy', $flashSale) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-delete" 
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this flash sale?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <i class="fas fa-bolt"></i>
                                    <h5 class="text-muted">No Flash Sales Found</h5>
                                    <p class="text-muted mb-3">Create your first flash sale to start offering limited-time discounts</p>
                                    <a href="{{ route('admin.flash-sales.create') }}" class="create-btn">
                                        <i class="fas fa-plus me-2"></i> Create New Flash Sale
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {{ $flashSales->links() }}
    </div>
</div>
@endsection 