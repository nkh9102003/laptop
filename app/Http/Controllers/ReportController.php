<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Number of customers
        $totalCustomers = User::where('role', 'customer')->count();

        // Number of orders
        $totalOrders = Order::count();

        // Number of products
        $totalProducts = Product::count();

        // Total revenue
        $totalRevenue = DB::table('payments')->sum('amount');

        // Total revenue by brand
        $brandRevenue = DB::table('order_items')
            ->select('products.brand_id', 'brands.name as brand_name', DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid')
            ->groupBy('products.brand_id', 'brands.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Revenue by date from payments
        $revenueByDate = DB::table('payments')
            ->select(DB::raw('DATE(payment_date) as date, SUM(amount) as total_revenue'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)  // Last 30 days
            ->get();

        // Revenue by month from payments
        $revenueByMonth = DB::table('payments')
            ->select(DB::raw('YEAR(payment_date) as year, MONTH(payment_date) as month, SUM(amount) as total_revenue'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)  // Last 12 months
            ->get();

        // Revenue by year from payments
        $revenueByYear = DB::table('payments')
            ->select(DB::raw('YEAR(payment_date) as year, SUM(amount) as total_revenue'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->take(5)  // Last 5 years
            ->get();
        
        // Revenue by payment method
        $revenueByPaymentMethod = DB::table('payments')
            ->select('payment_method', DB::raw('SUM(amount) as total_revenue'))
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.index', compact(
            'totalCustomers',
            'totalOrders',
            'totalProducts',
            'totalRevenue',
            'brandRevenue',
            'revenueByDate',
            'revenueByMonth',
            'revenueByYear',
            'revenueByPaymentMethod'
        ));
    }

    public function salesByProduct()
    {
        $salesByProduct = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.price * order_items.quantity) as total_sales'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sales', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.sales-by-product', compact('salesByProduct'));
    }
}