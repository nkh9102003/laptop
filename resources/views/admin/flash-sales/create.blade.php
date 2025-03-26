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
    
    .card-header-gradient {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        color: white;
    }
    
    .form-section {
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        overflow: hidden;
    }
    
    .form-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15);
    }
    
    .form-section-header {
        background-color: #f8f9fc;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .form-section-body {
        padding: 1.5rem;
        background: white;
    }
    
    .product-item {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid #e3e6f0;
        position: relative;
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .product-item:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-color: #c2cbe5;
    }
    
    .remove-product {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.2s;
    }
    
    .remove-product:hover {
        transform: scale(1.1);
    }
    
    .add-product-btn {
        background: linear-gradient(to right, #1cc88a, #20c997);
        border: none;
        border-radius: 0.5rem;
        color: white;
        font-weight: bold;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .add-product-btn:hover {
        background: linear-gradient(to right, #169d69, #1aa179);
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .submit-btn {
        background: linear-gradient(to right, #4e73df, #6b5ecd);
        border: none;
        border-radius: 0.5rem;
        color: white;
        font-weight: bold;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .submit-btn:hover {
        background: linear-gradient(to right, #3a5fc7, #5849b1);
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .form-control, .form-select {
        border-radius: 0.5rem;
        padding: 0.6rem 1rem;
        border-color: #e3e6f0;
        transition: all 0.2s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .input-group-text {
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
        border-color: #e3e6f0;
        background-color: #f8f9fc;
    }
    
    .datetime-input {
        position: relative;
    }
    
    .datetime-input:after {
        content: '\f133';
        font-family: 'Font Awesome 5 Free';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #b7b9cc;
        pointer-events: none;
    }
    
    /* Custom Switch Styling */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }
    
    .form-switch .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="flash-sale-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Create Flash Sale</h1>
            <p class="mb-0 mt-2 opacity-75">
                <i class="fas fa-info-circle me-1"></i> Create a new limited-time promotion
            </p>
        </div>
        <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.flash-sales.store') }}" method="POST">
        @csrf
        
        <div class="row gx-4">
            <div class="col-lg-6">
                <div class="form-section mb-4">
                    <div class="form-section-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Basic Information
                        </h5>
                    </div>
                    <div class="form-section-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Flash Sale Name</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-bolt text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Enter a catchy name"
                                       required>
                            </div>
                            @error('name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-align-left text-primary"></i>
                                </span>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3" 
                                          placeholder="Describe the flash sale">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                            <div class="text-muted small mt-1">
                                <i class="fas fa-lightbulb me-1"></i> A good description helps customers understand the value of your flash sale
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-4">
                    <div class="form-section-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Schedule
                        </h5>
                    </div>
                    <div class="form-section-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_time" class="form-label fw-bold">Start Time</label>
                                <div class="input-group datetime-input">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-day text-primary"></i>
                                    </span>
                                    <input type="datetime-local" 
                                           class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" 
                                           name="start_time" 
                                           value="{{ old('start_time') }}" 
                                           required>
                                </div>
                                @error('start_time')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_time" class="form-label fw-bold">End Time</label>
                                <div class="input-group datetime-input">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    </span>
                                    <input type="datetime-local" 
                                           class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" 
                                           name="end_time" 
                                           value="{{ old('end_time') }}" 
                                           required>
                                </div>
                                @error('end_time')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
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
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active"> Active
                            </label>
                            <div class="alert alert-info p-2 mt-2 small">
                                <i class="fas fa-info-circle me-1"></i> A flash sale will only be considered active if this is checked AND the current time is between start and end times.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-section mb-4">
                    <div class="form-section-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-tags me-2 text-primary"></i>Products on Sale
                        </h5>
                    </div>
                    <div class="form-section-body">
                        <div id="products-container">
                            @if(old('products'))
                                @foreach(old('products') as $index => $product)
                                    <div class="product-item">
                                        <button type="button" class="btn btn-sm btn-danger remove-product" 
                                                title="Remove Product">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Select Product</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-box text-primary"></i>
                                                </span>
                                                <select name="products[{{ $index }}][id]" 
                                                        class="form-select product-select" 
                                                        required>
                                                    <option value="">Select a product</option>
                                                    @foreach($products as $p)
                                                        <option value="{{ $p->id }}" 
                                                                {{ $product['id'] == $p->id ? 'selected' : '' }}
                                                                data-price="{{ $p->price }}">
                                                            {{ $p->name }} - ${{ number_format($p->price, 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Discount Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-dollar-sign text-primary"></i>
                                                    </span>
                                                    <input type="number" 
                                                           name="products[{{ $index }}][discount_price]" 
                                                           class="form-control discount-price" 
                                                           value="{{ $product['discount_price'] }}" 
                                                           step="0.01" 
                                                           min="0" 
                                                           required>
                                                </div>
                                                <div class="discount-percentage text-success mt-1 small"></div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Quantity Limit</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-cubes text-primary"></i>
                                                    </span>
                                                    <input type="number" 
                                                           name="products[{{ $index }}][quantity_limit]" 
                                                           class="form-control" 
                                                           value="{{ $product['quantity_limit'] ?? '' }}" 
                                                           placeholder="No limit"
                                                           min="1">
                                                </div>
                                                <div class="text-muted small mt-1">Leave empty for unlimited quantity</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="product-item">
                                    <button type="button" class="btn btn-sm btn-danger remove-product" 
                                            title="Remove Product">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Select Product</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-box text-primary"></i>
                                            </span>
                                            <select name="products[0][id]" class="form-select product-select" required>
                                                <option value="">Select a product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Discount Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-dollar-sign text-primary"></i>
                                                </span>
                                                <input type="number" 
                                                       name="products[0][discount_price]" 
                                                       class="form-control discount-price" 
                                                       step="0.01" 
                                                       min="0" 
                                                       required>
                                            </div>
                                            <div class="discount-percentage text-success mt-1 small"></div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Quantity Limit</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-cubes text-primary"></i>
                                                </span>
                                                <input type="number" 
                                                       name="products[0][quantity_limit]" 
                                                       class="form-control" 
                                                       placeholder="No limit"
                                                       min="1">
                                            </div>
                                            <div class="text-muted small mt-1">Leave empty for unlimited quantity</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <button type="button" class="add-product-btn w-100" id="add-product">
                            <i class="fas fa-plus me-2"></i> Add Another Product
                        </button>
                        
                        <div class="alert alert-info p-2 mt-3 small text-center">
                            <i class="fas fa-lightbulb me-1"></i> Add products you want to include in this flash sale
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
            <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-outline-secondary px-4 py-2">
                <i class="fas fa-times me-2"></i> Cancel
            </a>
            <button type="submit" class="submit-btn px-4 py-2">
                <i class="fas fa-bolt me-2"></i> Create Flash Sale
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('products-container');
    const addButton = document.getElementById('add-product');
    let productCount = container.children.length;

    // Update discount percentages
    function updateDiscountPercentages() {
        document.querySelectorAll('.product-item').forEach(item => {
            const select = item.querySelector('.product-select');
            const discountInput = item.querySelector('.discount-price');
            const percentageDisplay = item.querySelector('.discount-percentage');
            
            if (select.selectedIndex > 0 && discountInput.value) {
                const option = select.options[select.selectedIndex];
                const originalPrice = parseFloat(option.dataset.price);
                const discountPrice = parseFloat(discountInput.value);
                
                if (originalPrice > 0 && discountPrice < originalPrice) {
                    const percentage = Math.round(((originalPrice - discountPrice) / originalPrice) * 100);
                    percentageDisplay.innerHTML = `<i class="fas fa-tag me-1"></i> ${percentage}% off`;
                    
                    // Set color based on discount amount
                    if (percentage >= 50) {
                        percentageDisplay.className = 'discount-percentage text-danger mt-1 small fw-bold';
                    } else if (percentage >= 25) {
                        percentageDisplay.className = 'discount-percentage text-warning mt-1 small fw-bold';
                    } else {
                        percentageDisplay.className = 'discount-percentage text-success mt-1 small';
                    }
                } else {
                    percentageDisplay.innerHTML = '';
                }
            }
        });
    }
    
    // Initialize discount percentages
    updateDiscountPercentages();

    // Add product button handler
    addButton.addEventListener('click', function() {
        const template = container.children[0].cloneNode(true);
        
        // Update indices
        template.querySelectorAll('[name*="products[0]"]').forEach(input => {
            input.name = input.name.replace(/products\[\d+\]/, `products[${productCount}]`);
        });

        // Clear values
        template.querySelectorAll('input, select').forEach(input => {
            input.value = '';
        });
        
        // Clear discount percentage
        template.querySelector('.discount-percentage').innerHTML = '';

        // Add with animation
        template.style.opacity = '0';
        template.style.transform = 'translateY(20px)';
        container.appendChild(template);
        
        // Trigger reflow
        void template.offsetWidth;
        
        // Add the animation
        template.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        template.style.opacity = '1';
        template.style.transform = 'translateY(0)';
        
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
                productItem.style.transform = "translateY(20px)";
                setTimeout(() => {
                    productItem.remove();
                }, 300);
            } else {
                // Don't remove the last product, just reset its values
                const lastProduct = container.children[0];
                lastProduct.querySelectorAll('input, select').forEach(input => {
                    input.value = '';
                });
                lastProduct.querySelector('.discount-percentage').innerHTML = '';
                
                // Show a small tooltip or message
                const alert = document.createElement('div');
                alert.className = 'alert alert-warning py-2 mt-2 mb-0 small text-center fade show';
                alert.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> At least one product is required for a flash sale.';
                lastProduct.appendChild(alert);
                
                setTimeout(() => {
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 300);
                }, 3000);
            }
        }
    });
    
    // Update discount percentage when value changes
    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || 
            e.target.classList.contains('discount-price')) {
            updateDiscountPercentages();
        }
    });
    
    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('discount-price')) {
            updateDiscountPercentages();
        }
    });
});
</script>
@endpush
@endsection 