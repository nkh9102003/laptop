<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SpecificationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\FlashSaleFrontendController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'customerIndex'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'customerShow'])->name('products.show');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('admin-auth')->prefix('admin')->group(function () {
    Route::resource('flash-sales', FlashSaleController::class, [ 'as' => 'admin']);
    Route::resource('products', ProductController::class, [ 'as' => 'admin']);
    Route::resource('brands', BrandController::class, [ 'as' => 'admin']);
    Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('reports/sales-by-product', [ReportController::class, 'salesByProduct'])->name('admin.reports.sales-by-product');
    
    // Specification routes
    Route::get('specifications/types', [SpecificationController::class, 'manageTypes'])->name('admin.specifications.types');
    
    // Category routes
    Route::post('specifications/categories', [SpecificationController::class, 'storeCategory'])->name('admin.specifications.storeCategory');
    Route::put('specifications/categories/{category}', [SpecificationController::class, 'updateCategory'])->name('admin.specifications.updateCategory');
    Route::delete('specifications/categories/{category}', [SpecificationController::class, 'destroyCategory'])->name('admin.specifications.destroyCategory');
    
    // Specification type routes
    Route::post('specifications/types', [SpecificationController::class, 'storeType'])->name('admin.specifications.storeType');
    Route::put('specifications/types/{type}', [SpecificationController::class, 'updateType'])->name('admin.specifications.updateType');
    Route::delete('specifications/types/{type}', [SpecificationController::class, 'destroyType'])->name('admin.specifications.destroyType');
    
    Route::get('products/{product}/specifications', [SpecificationController::class, 'edit'])->name('admin.specifications.edit');
    Route::put('products/{product}/specifications', [SpecificationController::class, 'update'])->name('admin.specifications.update');
});

Route::middleware(['auth'])->group(function(){
    // Customer profile routes
    Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::put('/profile', [CustomerController::class, 'update'])->name('customer.update');
    Route::put('/profile/password', [CustomerController::class, 'updatePassword'])->name('customer.password.update');
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'customerIndex'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    
    // PayPal routes
    Route::get('/payment/{order}/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/{order}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{order}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Language routes
Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Frontend Flash Sale Routes
Route::get('flash-sales', [FlashSaleFrontendController::class, 'index'])->name('flash-sales.index');
Route::get('flash-sales/{flashSale}', [FlashSaleFrontendController::class, 'show'])->name('flash-sales.show');
    
