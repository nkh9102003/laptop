@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Manage Specification Categories & Types</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif
                    
                    <!-- Categories Management -->
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add New Category</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.specifications.storeCategory') }}" method="POST">
                                        @csrf
                                        
                                        <div class="form-group mb-3">
                                            <label for="category_name">Internal Name (no spaces)</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="category_name" name="name" value="{{ old('name') }}" required>
                                            <small class="form-text text-muted">Used internally, e.g., "processor", "memory"</small>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="category_display_name">Display Name</label>
                                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="category_display_name" name="display_name" value="{{ old('display_name') }}" required>
                                            <small class="form-text text-muted">Shown to users, e.g., "Processor", "Memory"</small>
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="category_description">Description (optional)</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="category_description" name="description" rows="2">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="category_display_order">Display Order</label>
                                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="category_display_order" name="display_order" value="{{ old('display_order', 0) }}">
                                            <small class="form-text text-muted">Lower numbers appear first</small>
                                            @error('display_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Add Category</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Existing Categories</h5>
                                </div>
                                <div class="card-body">
                                    @if($categories->isEmpty())
                                        <p class="text-muted">No categories defined yet.</p>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Display Name</th>
                                                        <th>Order</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($categories as $category)
                                                    <tr>
                                                        <td>{{ $category->name }}</td>
                                                        <td>{{ $category->display_name }}</td>
                                                        <td>{{ $category->display_order }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                                                Edit
                                                            </button>
                                                            
                                                            <form action="{{ route('admin.specifications.destroyCategory', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category? This will remove the category association from all specification types.')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    
                                                    <!-- Edit Category Modal -->
                                                    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Edit {{ $category->display_name }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('admin.specifications.updateCategory', $category->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="form-group mb-3">
                                                                            <label>Internal Name</label>
                                                                            <input type="text" class="form-control" value="{{ $category->name }}" disabled>
                                                                            <small class="form-text text-muted">Internal name cannot be changed</small>
                                                                        </div>
                                                                        
                                                                        <div class="form-group mb-3">
                                                                            <label for="edit_category_display_name{{ $category->id }}">Display Name</label>
                                                                            <input type="text" class="form-control" id="edit_category_display_name{{ $category->id }}" name="display_name" value="{{ $category->display_name }}" required>
                                                                        </div>
                                                                        
                                                                        <div class="form-group mb-3">
                                                                            <label for="edit_category_description{{ $category->id }}">Description (optional)</label>
                                                                            <textarea class="form-control" id="edit_category_description{{ $category->id }}" name="description" rows="2">{{ $category->description }}</textarea>
                                                                        </div>
                                                                        
                                                                        <div class="form-group mb-3">
                                                                            <label for="edit_category_display_order{{ $category->id }}">Display Order</label>
                                                                            <input type="number" class="form-control" id="edit_category_display_order{{ $category->id }}" name="display_order" value="{{ $category->display_order }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Specification Types Management -->
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add New Specification Type</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.specifications.storeType') }}" method="POST">
                                        @csrf
                                        
                                        <div class="form-group mb-3">
                                            <label for="category_id">Category</label>
                                            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                                <option value="">Select a category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->display_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="name">Internal Name (no spaces)</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                            <small class="form-text text-muted">Used internally, e.g., "processor_speed", "ram_size"</small>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="display_name">Display Name</label>
                                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" name="display_name" value="{{ old('display_name') }}" required>
                                            <small class="form-text text-muted">Shown to users, e.g., "Processor Speed", "RAM Size"</small>
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="unit">Unit (optional)</label>
                                            <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit') }}">
                                            <small class="form-text text-muted">E.g., "GHz", "GB", "inches"</small>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="description">Description (optional)</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="display_order">Display Order</label>
                                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', 0) }}">
                                            <small class="form-text text-muted">Lower numbers appear first within a category</small>
                                            @error('display_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Add Specification Type</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Existing Specification Types</h5>
                                </div>
                                <div class="card-body">
                                    @if($categories->isEmpty())
                                        <p class="text-muted">No specification types defined yet. First create a category.</p>
                                    @else
                                        <div class="accordion" id="specificationTypesAccordion">
                                            @foreach($categories as $category)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading{{ $category->id }}">
                                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $category->id }}">
                                                            {{ $category->display_name }} ({{ $category->specificationTypes->count() }} types)
                                                        </button>
                                                    </h2>
                                                    <div id="collapse{{ $category->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $category->id }}" data-bs-parent="#specificationTypesAccordion">
                                                        <div class="accordion-body">
                                                            @if($category->specificationTypes->isEmpty())
                                                                <p class="text-muted">No specification types in this category yet.</p>
                                                            @else
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Name</th>
                                                                                <th>Display Name</th>
                                                                                <th>Unit</th>
                                                                                <th>Order</th>
                                                                                <th>Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($category->specificationTypes as $type)
                                                                            <tr>
                                                                                <td>{{ $type->name }}</td>
                                                                                <td>{{ $type->display_name }}</td>
                                                                                <td>{{ $type->unit ?? '-' }}</td>
                                                                                <td>{{ $type->display_order }}</td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $type->id }}">
                                                                                        Edit
                                                                                    </button>
                                                                                    
                                                                                    <form action="{{ route('admin.specifications.destroyType', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this specification type? This will remove all related product specifications.')">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                                                    </form>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <!-- Edit Modal -->
                                                                            <div class="modal fade" id="editModal{{ $type->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $type->id }}" aria-hidden="true">
                                                                                <div class="modal-dialog">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="editModalLabel{{ $type->id }}">Edit {{ $type->display_name }}</h5>
                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <form action="{{ route('admin.specifications.updateType', $type->id) }}" method="POST">
                                                                                            @csrf
                                                                                            @method('PUT')
                                                                                            <div class="modal-body">
                                                                                                <div class="form-group mb-3">
                                                                                                    <label>Internal Name</label>
                                                                                                    <input type="text" class="form-control" value="{{ $type->name }}" disabled>
                                                                                                    <small class="form-text text-muted">Internal name cannot be changed</small>
                                                                                                </div>
                                                                                                
                                                                                                <div class="form-group mb-3">
                                                                                                    <label for="edit_category_id{{ $type->id }}">Category</label>
                                                                                                    <select class="form-control" id="edit_category_id{{ $type->id }}" name="category_id" required>
                                                                                                        @foreach($categories as $cat)
                                                                                                            <option value="{{ $cat->id }}" {{ $type->category_id == $cat->id ? 'selected' : '' }}>
                                                                                                                {{ $cat->display_name }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                
                                                                                                <div class="form-group mb-3">
                                                                                                    <label for="edit_display_name{{ $type->id }}">Display Name</label>
                                                                                                    <input type="text" class="form-control" id="edit_display_name{{ $type->id }}" name="display_name" value="{{ $type->display_name }}" required>
                                                                                                </div>
                                                                                                
                                                                                                <div class="form-group mb-3">
                                                                                                    <label for="edit_unit{{ $type->id }}">Unit (optional)</label>
                                                                                                    <input type="text" class="form-control" id="edit_unit{{ $type->id }}" name="unit" value="{{ $type->unit }}">
                                                                                                </div>
                                                                                                
                                                                                                <div class="form-group mb-3">
                                                                                                    <label for="edit_description{{ $type->id }}">Description (optional)</label>
                                                                                                    <textarea class="form-control" id="edit_description{{ $type->id }}" name="description" rows="2">{{ $type->description }}</textarea>
                                                                                                </div>
                                                                                                
                                                                                                <div class="form-group mb-3">
                                                                                                    <label for="edit_display_order{{ $type->id }}">Display Order</label>
                                                                                                    <input type="number" class="form-control" id="edit_display_order{{ $type->id }}" name="display_order" value="{{ $type->display_order }}">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 