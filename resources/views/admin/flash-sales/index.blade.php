@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Flash Sales</h1>
        <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Flash Sale
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flashSales as $flashSale)
                            <tr>
                                <td>{{ $flashSale->name }}</td>
                                <td>{{ $flashSale->start_time->format('Y-m-d H:i') }}</td>
                                <td>{{ $flashSale->end_time->format('Y-m-d H:i') }}</td>
                                <td>
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
                                </td>
                                <td>{{ $flashSale->products->count() }} products</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.flash-sales.show', $flashSale) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.flash-sales.destroy', $flashSale) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this flash sale?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No flash sales found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $flashSales->links() }}
        </div>
    </div>
</div>
@endsection 