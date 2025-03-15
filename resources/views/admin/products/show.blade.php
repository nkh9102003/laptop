@extends('layouts.app')
  
@section('content')

<div class="card mt-5">
  <h2 class="card-header">Show Product</h2>
  <div class="card-body">
  
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.products.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        <a class="btn btn-success btn-sm" href="{{ route('admin.specifications.edit', $product->id) }}"><i class="fa fa-cog"></i> Manage Specifications</a>
    </div>
  
    <div class="row mt-4">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Name:</strong> <br/>
                {{ $product->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Brand:</strong> <br/>
                {{ $product->brand->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
            <div class="form-group">
                <strong>Description:</strong> <br/>
                {{ $product->description }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 mt-3">
            <div class="form-group">
                <strong>Stock:</strong> <br/>
                {{ $product->stock }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 mt-3">
            <div class="form-group">
                <strong>Price:</strong> <br/>
                {{ $product->price }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
            <div class="form-group">
                <strong>Image:</strong> <br/>
                <img src="{{ $product->image_url }}" width="200">
            </div>
        </div>
        
        <!-- Product Specifications -->
        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Product Specifications</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($product->specifications->isEmpty())
                        <p class="text-muted">No specifications have been added for this product.</p>
                        <a href="{{ route('admin.specifications.edit', $product->id) }}" class="btn btn-sm btn-primary">Add Specifications</a>
                    @else
                        @php
                            $groupedSpecs = $product->getSpecificationsByCategory();
                        @endphp
                        
                        <div class="accordion" id="specificationsAccordion">
                            @foreach($groupedSpecs as $categoryId => $data)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $categoryId }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $categoryId }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $categoryId }}">
                                            {{ $data['category']->display_name }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $categoryId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $categoryId }}" data-bs-parent="#specificationsAccordion">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        @foreach($data['specifications'] as $spec)
                                                        <tr>
                                                            <th style="width: 40%">{{ $spec->specificationType->display_name }}</th>
                                                            <td>{{ $spec->formatted_value }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
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
  
  </div>
</div>
@endsection