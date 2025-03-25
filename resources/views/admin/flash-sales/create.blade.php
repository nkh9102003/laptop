@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Flash Sale</h1>
        <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.flash-sales.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Flash Sale Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="start_time">Start Time</label>
                            <input type="datetime-local" 
                                   class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" 
                                   name="start_time" 
                                   value="{{ old('start_time') }}" 
                                   required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <input type="datetime-local" 
                                   class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" 
                                   name="end_time" 
                                   value="{{ old('end_time') }}" 
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <div class="form-check form-switch">
                                <!-- Hidden field to ensure is_active is always sent even when checkbox is unchecked -->
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                            <small class="text-muted">Note: A flash sale will only be considered active if this is checked AND the current time is between start and end times.</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Products</h5>
                            </div>
                            <div class="card-body">
                                <div id="products-container">
                                    @if(old('products'))
                                        @foreach(old('products') as $index => $product)
                                            <div class="product-item mb-3">
                                                <div class="form-group">
                                                    <label>Product</label>
                                                    <select name="products[{{ $index }}][id]" 
                                                            class="form-control product-select" 
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
                                                <div class="form-group">
                                                    <label>Discount Price</label>
                                                    <input type="number" 
                                                           name="products[{{ $index }}][discount_price]" 
                                                           class="form-control" 
                                                           value="{{ $product['discount_price'] }}" 
                                                           step="0.01" 
                                                           min="0" 
                                                           required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Quantity Limit (Optional)</label>
                                                    <input type="number" 
                                                           name="products[{{ $index }}][quantity_limit]" 
                                                           class="form-control" 
                                                           value="{{ $product['quantity_limit'] ?? '' }}" 
                                                           min="1">
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm remove-product">
                                                    Remove Product
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="product-item mb-3">
                                            <div class="form-group">
                                                <label>Product</label>
                                                <select name="products[0][id]" class="form-control product-select" required>
                                                    <option value="">Select a product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">
                                                            {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Discount Price</label>
                                                <input type="number" 
                                                       name="products[0][discount_price]" 
                                                       class="form-control" 
                                                       step="0.01" 
                                                       min="0" 
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label>Quantity Limit (Optional)</label>
                                                <input type="number" 
                                                       name="products[0][quantity_limit]" 
                                                       class="form-control" 
                                                       min="1">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-success" id="add-product">
                                    <i class="fas fa-plus"></i> Add Another Product
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create Flash Sale</button>
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

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            if (container.children.length > 1) {
                e.target.closest('.product-item').remove();
            }
        }
    });
});
</script>
@endpush
@endsection 