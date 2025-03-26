<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\FlashSale;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch active flash sales
        $activeFlashSale = FlashSale::with(['products' => function ($query) {
            $query->where('stock', '>', 0)
                  ->take(6);
        }])
        ->where('is_active', true)
        ->where('start_time', '<=', now())
        ->where('end_time', '>=', now())
        ->orderBy('end_time', 'asc')
        ->first();
        
        // Fetch some featured products with their active flash sales
        $featuredProducts = Product::with(['brand', 'activeFlashSales'])
            ->latest()
            ->take(8)
            ->get();
        
        return view('home', compact('featuredProducts', 'activeFlashSale'));
    }
}