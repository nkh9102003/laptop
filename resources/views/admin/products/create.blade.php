@extends('layouts.app')
@section('content')
       
       <div class="card mt-5">
         <h2 class="card-header">Add New Product</h2>
         <div class="card-body">
         
           <div class="d-grid gap-2 d-md-flex justify-content-md-end">
               <a class="btn btn-primary btn-sm" href="{{ route('admin.products.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
           </div>
         
           <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
               @csrf
         
               <div class="mb-3">
                   <label for="inputName" class="form-label"><strong>Name:</strong></label>
                   <input 
                       type="text" 
                       name="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="inputName" 
                       placeholder="Name">
                   @error('name')
                       <div class="form-text text-danger">{{ $message }}</div>
                   @enderror
               </div>
  
               <div class="mb-3">
                   <label for="inputDescription" class="form-label"><strong>Description:</strong></label>
                   <textarea 
                       name="description" 
                       class="form-control @error('description') is-invalid @enderror" 
                       id="inputDescription" 
                       placeholder="Description"></textarea>
                   @error('description')
                       <div class="form-text text-danger">{{ $message }}</div>
                   @enderror
               </div>

               <div class="mb-3">
                   <label for="inputStock" class="form-label"><strong>Stock:</strong></label>
                   <input 
                       type="text" 
                       name="stock" 
                       class="form-control @error('stock') is-invalid @enderror" 
                       id="inputStock" 
                       placeholder="Stock">
               </div>
  
               <div class="mb-3">
                   <label for="inputPrice" class="form-label"><strong>Price:</strong></label>
                   <input 
                       type="text" 
                       name="price" 
                       class="form-control @error('price') is-invalid @enderror" 
                       id="inputPrice" 
                       placeholder="Price">
                   @error('price')
                       <div class="form-text text-danger">{{ $message }}</div>
                   @enderror
               </div>
  
               <div class="mb-3">
                   <label for="inputBrand" class="form-label"><strong>Brand:</strong></label>
                   <select 
                       name="brand_id" 
                       class="form-control @error('brand_id') is-invalid @enderror" 
                       id="inputBrand">
                       <option value="">Select Brand</option>
                       @foreach($brands as $brand)
                           <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                       @endforeach
                   </select>
                   @error('brand_id')
                       <div class="form-text text-danger">{{ $message }}</div>
                   @enderror
               </div>
               <div class="mb-3">
                  <label for="inputImage" class="form-label"><strong>Image:</strong></label>
                  <input 
                      type="file" 
                      name="image" 
                      class="form-control @error('image') is-invalid @enderror" 
                      id="inputImage" accept=".png, .jpg, .jpeg">
                  @error('image')
                      <div class="form-text text-danger">{{ $message }}</div>
                  @enderror
              </div>
               <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Submit</button>            
           </form>
         
         </div>
       </div>
       @endsection