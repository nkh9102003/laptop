<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    /**
     * Get the path where product images are stored
     */
    protected function getImagePath()
    {
        return Config::get('filesystems.paths.product_images', 'images/products/');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($brandId = $request->input('brand')) {
            $query->where('brand_id', $brandId);
        }

        $products = $query->with('brand')->latest()->paginate(5);
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'brands'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        return view('admin.products.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $imageName = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->getImagePath()), $imageName);
            $input['image'] = $imageName;
        }

        Product::create($input);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $product->load('specifications.specificationType');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();
        if ($image = $request->file('image')) {
            // Delete old image if exists
            if ($product->image && File::exists(public_path($this->getImagePath() . $product->image))) {
                File::delete(public_path($this->getImagePath() . $product->image));
            }
            
            $imageName = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->getImagePath()), $imageName);
            $input['image'] = $imageName;
        } else {
            unset($input['image']);
        }
        $product->update($input);

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Delete the product image if it exists
        if ($product->image) {
            $imagePath = public_path($this->getImagePath() . $product->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công.');
    }
    
    public function customerIndex(Request $request): View
    {
        $query = Product::query();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($brandId = $request->input('brand')) {
            $query->where('brand_id', $brandId);
        }

        $products = $query->with('brand')->latest()->paginate(12);
        $brands = Brand::all();

        return view('products.index', compact('products', 'brands'));
    }

    /**
     * Display the specified product for customers.
     */
    public function customerShow(Product $product): View
    {
        $product->load('specifications.specificationType');
        return view('products.show', compact('product'));
    }
}
