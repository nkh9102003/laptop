<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class BrandController extends Controller
{
    /**
     * Get the path where brand logos are stored
     */
    protected function getLogoPath()
    {
        return Config::get('filesystems.paths.brand_logos', 'images/brands/');
    }

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
        $input = $request->validated();

        if ($logo = $request->file('logo')) {
            $logoName = Str::slug($request->name) . '-' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path($this->getLogoPath()), $logoName);
            $input['logo'] = $logoName;
        }

        Brand::create($input);
           
        return redirect()->route('admin.brands.index')
                         ->with('success', 'Brand created successfully.');
    }
  
    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): View
    {
        return view('admin.brands.show', compact('brand'));
    }
  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }
  
    /**
     * Update the specified resource in storage.
     */
    public function update(BrandUpdateRequest $request, Brand $brand): RedirectResponse
    {
        $input = $request->validated();

        if ($logo = $request->file('logo')) {
            // Delete old logo if exists
            if ($brand->logo && File::exists(public_path($this->getLogoPath() . $brand->logo))) {
                File::delete(public_path($this->getLogoPath() . $brand->logo));
            }
            
            $logoName = Str::slug($request->name) . '-' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path($this->getLogoPath()), $logoName);
            $input['logo'] = $logoName;
        } else {
            unset($input['logo']);
        }

        $brand->update($input);
          
        return redirect()->route('admin.brands.index')
                        ->with('success', 'Brand updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        // Delete the brand logo if it exists
        if ($brand->logo) {
            $logoPath = public_path($this->getLogoPath() . $brand->logo);
            if (File::exists($logoPath)) {
                File::delete($logoPath);
            }
        }
        
        $brand->delete();
           
        return redirect()->route('admin.brands.index')
                        ->with('success', 'Brand deleted successfully');
    }
}
