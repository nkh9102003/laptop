<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SpecificationType;
use App\Models\ProductSpecification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SpecificationController extends Controller
{
    /**
     * Show the form for managing specifications for a product
     */
    public function edit(Product $product): View
    {
        $specificationTypes = SpecificationType::orderBy('display_name')->get();
        $productSpecifications = $product->specifications->keyBy('specification_type_id');
        
        return view('admin.specifications.edit', compact('product', 'specificationTypes', 'productSpecifications'));
    }
    
    /**
     * Update the specifications for a product
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $specValues = $request->input('specifications', []);
        
        foreach ($specValues as $typeId => $value) {
            if (empty($value)) {
                // Delete specification if value is empty
                ProductSpecification::where('product_id', $product->id)
                    ->where('specification_type_id', $typeId)
                    ->delete();
            } else {
                // Update or create specification
                ProductSpecification::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'specification_type_id' => $typeId
                    ],
                    ['value' => $value]
                );
            }
        }
        
        return redirect()->route('admin.products.show', $product->id)
            ->with('success', 'Product specifications updated successfully.');
    }
    
    /**
     * Show the form for managing specification types
     */
    public function manageTypes(): View
    {
        $specificationTypes = SpecificationType::orderBy('display_name')->get();
        return view('admin.specifications.types', compact('specificationTypes'));
    }
    
    /**
     * Store a new specification type
     */
    public function storeType(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|alpha_dash|unique:specification_types',
            'display_name' => 'required',
            'unit' => 'nullable',
            'description' => 'nullable',
        ]);
        
        SpecificationType::create($request->all());
        
        return redirect()->route('admin.specifications.types')
            ->with('success', 'Specification type created successfully.');
    }
    
    /**
     * Update a specification type
     */
    public function updateType(Request $request, SpecificationType $type): RedirectResponse
    {
        $request->validate([
            'display_name' => 'required',
            'unit' => 'nullable',
            'description' => 'nullable',
        ]);
        
        $type->update($request->only(['display_name', 'unit', 'description']));
        
        return redirect()->route('admin.specifications.types')
            ->with('success', 'Specification type updated successfully.');
    }
    
    /**
     * Delete a specification type
     */
    public function destroyType(SpecificationType $type): RedirectResponse
    {
        // This will cascade delete all product specifications of this type
        $type->delete();
        
        return redirect()->route('admin.specifications.types')
            ->with('success', 'Specification type deleted successfully.');
    }
} 