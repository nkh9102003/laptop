@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Flash Sale</h1>
        <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary bg-gradient">
            <h6 class="m-0 font-weight-bold text-white">Flash Sale Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.flash-sales.update', $flashSale) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row gx-4">
                    <div class="col-lg-6">
                        <div class="p-4 border rounded shadow-sm mb-4 bg-white">
                            <h5 class="border-bottom pb-2 mb-3">Basic Information</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Flash Sale Name</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $flashSale->name) }}" 
                                       placeholder="Enter a catchy name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3" 
                                          placeholder="Describe the flash sale">{{ old('description', $flashSale->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="p-4 border rounded shadow-sm mb-4 bg-white">
                            <h5 class="border-bottom pb-2 mb-3">Schedule</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" 
                                           name="start_time" 
                                           value="{{ old('start_time', $flashSale->start_time->format('Y-m-d\TH:i')) }}" 
                                           required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" 
                                           name="end_time" 
                                           value="{{ old('end_time', $flashSale->end_time->format('Y-m-d\TH:i')) }}" 
                                           required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-check form-switch mt-2">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $flashSale->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                                <div class="text-muted small mt-1">A flash sale will only be considered active if this is checked AND the current time is between start and end times.</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-tags me-2"></i>Products on Sale
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="products-container">
                                    @if(old('products'))
                                        @foreach(old('products') as $index => $product)
                                            <div class="product-item p-3 mb-3 border rounded position-relative">
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-product" 
                                                        title="Remove Product">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Product</label>
                                                    <select name="products[{{ $index }}][id]" 
                                                            class="form-select product-select" 
                                                            required>
                                                        <option value="">Select a product</option>
                                                        @foreach($products as $p)
                                                            <option value="{{ $p->id }}" 
                                                                    {{ $product['id'] == $p->id ? 'selected' : '' }}>
                                                                {{ $p->name }} - ${{ number_format($p->price, 2) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Discount Price</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" 
                                                                   name="products[{{ $index }}][discount_price]" 
                                                                   class="form-control" 
                                                                   value="{{ $product['discount_price'] }}" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Quantity Limit</label>
                                                        <input type="number" 
                                                               name="products[{{ $index }}][quantity_limit]" 
                                                               class="form-control" 
                                                               value="{{ $product['quantity_limit'] ?? '' }}" 
                                                               placeholder="No limit"
                                                               min="1">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach($flashSale->products as $index => $product)
                                            <div class="product-item p-3 mb-3 border rounded position-relative">
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-product" 
                                                        title="Remove Product">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Product</label>
                                                    <select name="products[{{ $index }}][id]" 
                                                            class="form-select product-select" 
                                                            required>
                                                        <option value="">Select a product</option>
                                                        @foreach($products as $p)
                                                            <option value="{{ $p->id }}" 
                                                                    {{ $product->id == $p->id ? 'selected' : '' }}>
                                                                {{ $p->name }} - ${{ number_format($p->price, 2) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Discount Price</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" 
                                                                   name="products[{{ $index }}][discount_price]" 
                                                                   class="form-control" 
                                                                   value="{{ $product->pivot->discount_price }}" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Quantity Limit</label>
                                                        <input type="number" 
                                                               name="products[{{ $index }}][quantity_limit]" 
                                                               class="form-control" 
                                                               value="{{ $product->pivot->quantity_limit ?? '' }}" 
                                                               placeholder="No limit"
                                                               min="1">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                
                                <button type="button" class="btn btn-success w-100" id="add-product">
                                    <i class="fas fa-plus me-2"></i> Add Another Product
                                </button>
                                
                                <div class="text-muted small mt-2 text-center">
                                    Add products you want to include in this flash sale
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-outline-secondary me-md-2">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Update Flash Sale
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('products-container');
    const addButton = document.getElementById('add-product');
    let productCount = container.children.length;

    // Add product button handler
    addButton.addEventListener('click', function() {
        const template = container.children[0].cloneNode(true);
        
        // Update indices
        template.querySelectorAll('[name*="products[0]"]').forEach(input => {
            input.name = input.name.replace('products[0]', `products[${productCount}]`);
        });

        // Clear values
        template.querySelectorAll('input, select').forEach(input => {
            input.value = '';
        });

        container.appendChild(template);
        productCount++;
    });

    // Remove product button handler
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product') || e.target.closest('.remove-product')) {
            if (container.children.length > 1) {
                const productItem = e.target.closest('.product-item');
                // Add fade-out animation
                productItem.style.transition = "all 0.3s";
                productItem.style.opacity = 0;
                setTimeout(() => {
                    productItem.remove();
                }, 300);
            } else {
                // Don't remove the last product, just reset its values
                const lastProduct = container.children[0];
                lastProduct.querySelectorAll('input, select').forEach(input => {
                    input.value = '';
                });
                // Show a small tooltip or message
                alert('At least one product is required for a flash sale.');
            }
        }
    });
});
</script>
@endpush
@endsection 