@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Manage Specification Types</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add New Specification Type</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.specifications.storeType') }}" method="POST">
                                        @csrf
                                        
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
                                        
                                        <button type="submit" class="btn btn-primary">Add Specification Type</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Existing Specification Types</h5>
                                </div>
                                <div class="card-body">
                                    @if($specificationTypes->isEmpty())
                                        <p class="text-muted">No specification types defined yet.</p>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Display Name</th>
                                                        <th>Unit</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($specificationTypes as $type)
                                                    <tr>
                                                        <td>{{ $type->name }}</td>
                                                        <td>{{ $type->display_name }}</td>
                                                        <td>{{ $type->unit ?? '-' }}</td>
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
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 