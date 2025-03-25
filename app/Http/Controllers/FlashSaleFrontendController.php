<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use Illuminate\Http\Request;

class FlashSaleFrontendController extends Controller
{
    public function index()
    {
        $activeFlashSales = FlashSale::with(['products' => function ($query) {
            $query->where('stock', '>', 0);
        }])
        ->where('is_active', true)
        ->where('start_time', '<=', now())
        ->where('end_time', '>=', now())
        ->orderBy('end_time', 'asc')
        ->get();

        return view('flash-sales.index', compact('activeFlashSales'));
    }

    public function show(FlashSale $flashSale)
    {
        if (!$flashSale->isActive()) {
            abort(404);
        }

        $flashSale->load(['products' => function ($query) {
            $query->where('stock', '>', 0);
        }]);

        return view('flash-sales.show', compact('flashSale'));
    }
} 