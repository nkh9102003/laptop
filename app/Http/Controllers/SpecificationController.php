<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SpecificationType;
use App\Models\SpecificationCategory;
use App\Models\ProductSpecification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class SpecificationController extends Controller
{
    /**
     * Show the form for managing specifications for a product
     */
    public function edit(Product $product): View
    {
        // Get all categories with their specification types
        $categories = SpecificationCategory::with(['specificationTypes' => function($query) {
            $query->orderBy('display_order');
        }])
        ->orderBy('display_order')
        ->get();
        
        // Get existing product specifications
        $productSpecifications = $product->specifications->keyBy('specification_type_id');
        
        return view('admin.specifications.edit', compact('product', 'categories', 'productSpecifications'));
    }
    
    /**
     * Update the specifications for a product
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        try {
            $specValues = $request->input('specifications', []);
            
            foreach ($specValues as $typeId => $value) {
                // Ensure typeId is an integer
                $typeId = (int) $typeId;
                
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
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating product specifications: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the specifications. Please try again.']);
        }
    }
    
    /**
     * Show the form for managing specification types and categories
     */
    public function manageTypes(): View
    {
        $categories = SpecificationCategory::with(['specificationTypes' => function($query) {
            $query->orderBy('display_order');
        }])
        ->orderBy('display_order')
        ->get();
        
        return view('admin.specifications.types', compact('categories'));
    }
    
    /**
     * Store a new specification category
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|alpha_dash|unique:specification_categories',
            'display_name' => 'required',
            'description' => 'nullable',
            'display_order' => 'nullable|integer',
        ]);
        
        SpecificationCategory::create($request->all());
        
        return redirect()->route('admin.specifications.types')
            ->with('success', 'Specification category created successfully.');
    }
    
    /**
     * Update a specification category
     */
    public function updateCategory(Request $request, SpecificationCategory $category): RedirectResponse
    {
        $request->validate([
            'display_name' => 'required',
            'description' => 'nullable',
            'display_order' => 'nullable|integer',
        ]);
        
        $category->update($request->only(['display_name', 'description', 'display_order']));
        
        return redirect()->route('admin.specifications.types')
            ->with('success', 'Specification category updated successfully.');
    }
    
    /**
     * Delete a specification category
     */
    public function destroyCategory(SpecificationCategory $category): RedirectResponse
    {
        // This will set category_id to null for all related specification types
        $category->delete();
        
        return redirect()->route('admin.specifications.types')
            ->with('success', 'Specification category deleted successfully.');
    }
    
    /**
     * Store a new specification type
     */
    public function storeType(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'alpha_dash',
                Rule::unique('specification_types')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                }),
            ],
            'display_name' => 'required',
            'category_id' => 'required|exists:specification_categories,id',
            'unit' => 'nullable',
            'description' => 'nullable',
            'display_order' => 'nullable|integer',
        ], [
            'name.unique' => 'A specification type with this name already exists in the selected category.',
        ]);
        
        try {
            SpecificationType::create($request->all());
            
            return redirect()->route('admin.specifications.types')
                ->with('success', 'Specification type created successfully.');
        } catch (QueryException $e) {
            // Check if the error is due to a unique constraint violation
            if ($e->getCode() == 23000) { // SQLSTATE code for integrity constraint violation
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['name' => 'A specification type with this name already exists in the selected category.']);
            }
            
            // Handle other database exceptions
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating the specification type.']);
        }
    }
    
    /**
     * Update a specification type
     */
    public function updateType(Request $request, SpecificationType $type): RedirectResponse
    {
        $request->validate([
            'display_name' => 'required',
            'category_id' => 'required|exists:specification_categories,id',
            'unit' => 'nullable',
            'description' => 'nullable',
            'display_order' => 'nullable|integer',
        ]);
        
        try {
            // Check if the category is being changed
            if ($type->category_id != $request->category_id) {
                // If changing category, check if there's already a type with the same name in the new category
                $existingType = SpecificationType::where('name', $type->name)
                    ->where('category_id', $request->category_id)
                    ->where('id', '!=', $type->id)
                    ->first();
                
                if ($existingType) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'A specification type with this name already exists in the selected category.']);
                }
            }
            
            $type->update($request->only(['display_name', 'category_id', 'unit', 'description', 'display_order']));
            
            return redirect()->route('admin.specifications.types')
                ->with('success', 'Specification type updated successfully.');
        } catch (QueryException $e) {
            // Check if the error is due to a unique constraint violation
            if ($e->getCode() == 23000) { // SQLSTATE code for integrity constraint violation
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'A specification type with this name already exists in the selected category.']);
            }
            
            // Handle other database exceptions
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the specification type.']);
        }
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