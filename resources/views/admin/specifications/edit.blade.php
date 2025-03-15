@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Edit Specifications for {{ $product->name }}</h4>
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.specifications.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if($categories->isEmpty())
                            <div class="alert alert-warning">
                                No specification categories defined yet. <a href="{{ route('admin.specifications.types') }}">Create some categories and specification types</a> first.
                            </div>
                        @else
                            <div class="accordion" id="specificationsAccordion">
                                @foreach($categories as $category)
                                    @if($category->specificationTypes->isNotEmpty())
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $category->id }}">
                                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $category->id }}">
                                                    {{ $category->display_name }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $category->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $category->id }}" data-bs-parent="#specificationsAccordion">
                                                <div class="accordion-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Specification</th>
                                                                    <th>Value</th>
                                                                    <th>Unit</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($category->specificationTypes as $type)
                                                                <tr>
                                                                    <td>
                                                                        <label for="spec_{{ $type->id }}">{{ $type->display_name }}</label>
                                                                        @if($type->description)
                                                                            <p class="text-muted small">{{ $type->description }}</p>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <input 
                                                                            type="text" 
                                                                            class="form-control" 
                                                                            id="spec_{{ $type->id }}" 
                                                                            name="specifications[{{ $type->id }}]" 
                                                                            value="{{ $productSpecifications->has($type->id) ? $productSpecifications[$type->id]->value : '' }}"
                                                                        >
                                                                    </td>
                                                                    <td>
                                                                        {{ $type->unit ?? '-' }}
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-success">Save Specifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 