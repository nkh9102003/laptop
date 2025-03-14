<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $brands = $query->latest()->paginate(10);

        return view('admin.brands.index', compact('brands'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.brands.create');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandStoreRequest $request): RedirectResponse
    {   
        Brand::create($request->validated());
           
        return redirect()->route('admin.brands.index')
                         ->with('success', 'Brand created successfully.');
    }
  
    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): View
    {
        return view('admin.brands.show',compact('brand'));
    }
  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit',compact('brand'));
    }
  
    /**
     * Update the specified resource in storage.
     */
    public function update(BrandUpdateRequest $request, Brand $brand): RedirectResponse
    {
        $brand->update($request->validated());
          
        return redirect()->route('admin.brands.index')
                        ->with('success','Brand updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();
           
        return redirect()->route('admin.brands.index')
                        ->with('success','Brand deleted successfully');
    }
}
