<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch some featured products or latest products
        $featuredProducts = Product::latest()->take(8)->get();
        return view('home', compact('featuredProducts'));
    }
}