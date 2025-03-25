<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::with('products')
            ->orderBy('start_time', 'desc')
            ->paginate(10);
        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.flash-sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'boolean',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.discount_price' => 'required|numeric|min:0',
            'products.*.quantity_limit' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $flashSale = FlashSale::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'is_active' => (bool)$request->input('is_active', 1),
            ]);

            foreach ($validated['products'] as $product) {
                $flashSale->products()->attach($product['id'], [
                    'discount_price' => $product['discount_price'],
                    'quantity_limit' => $product['quantity_limit'] ?? null,
                    'sold_count' => 0,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.flash-sales.index')
                ->with('success', 'Flash sale created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create flash sale. Please try again.');
        }
    }

    public function edit(FlashSale $flashSale)
    {
        $products = Product::all();
        $flashSale->load('products');
        return view('admin.flash-sales.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'boolean',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.discount_price' => 'required|numeric|min:0',
            'products.*.quantity_limit' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $flashSale->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'is_active' => (bool)$request->input('is_active', 0),
            ]);

            // Sync products with their new prices and limits
            $syncData = collect($validated['products'])->mapWithKeys(function ($product) {
                return [$product['id'] => [
                    'discount_price' => $product['discount_price'],
                    'quantity_limit' => $product['quantity_limit'] ?? null,
                    'sold_count' => 0, // Reset sold count for simplicity
                ]];
            })->toArray();

            $flashSale->products()->sync($syncData);

            DB::commit();

            return redirect()->route('admin.flash-sales.index')
                ->with('success', 'Flash sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update flash sale. Please try again: ' . $e->getMessage());
        }
    }

    public function destroy(FlashSale $flashSale)
    {
        try {
            $flashSale->delete();
            return redirect()->route('admin.flash-sales.index')
                ->with('success', 'Flash sale deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete flash sale. Please try again.');
        }
    }

    public function show(FlashSale $flashSale)
    {
        $flashSale->load('products');
        return view('admin.flash-sales.show', compact('flashSale'));
    }
} 