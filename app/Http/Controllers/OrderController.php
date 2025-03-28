<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\FlashSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'items.flashSale']);
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }


    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,paid,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }
    
    public function customerIndex()
    {
        // Get the current user ID
        $userId = Auth::id();
        
        // Get orders for this user with related models
        $orders = Order::where('user_id', $userId)
            ->with(['items.product', 'items.flashSale'])
            ->latest()
            ->paginate(10);
        
        return view('orders.index', compact('orders'));
    }


    public function store(Request $request)
    {
        $cart = $this->getCart();

        $request->validate([
            'payment_method' => 'required|in:COD,online',
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Calculate the correct total based on effective prices (including flash sale prices)
            $total = $cart->items->sum(function ($item) {
                return $item->effective_price * $item->quantity;
            });
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => 'processing',
                'payment_method' => $request->payment_method,
                'name' => $request->name,
                'contact' => $request->contact,
                'address' => $request->address,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total,
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
            ]);

            foreach ($cart->items as $item) {
                $product = Product::findOrFail($item->product_id);
                
                // Check if there's enough stock
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Not enough stock for product: {$product->name}");
                }

                // Subtract stock
                $product->stock -= $item->quantity;
                $product->save();

                // Create order item with flash sale information if applicable
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->effective_price, // Use the effective price (flash sale price if applicable)
                    'original_price' => $item->hasActiveDiscount() ? $product->price : null, // Store original price if discounted
                    'flash_sale_id' => $item->hasActiveDiscount() ? $item->flash_sale_id : null, // Store flash sale ID if applicable
                ]);
                
                // If this was a flash sale item, update the sold count in the flash sale
                if ($item->flash_sale_id) {
                    $flashSale = FlashSale::findOrFail($item->flash_sale_id);
                    $flashSaleItem = $flashSale->products()->where('product_id', $item->product_id)->first();
                    
                    if ($flashSaleItem) {
                        // Ensure the sold count is accurate
                        $flashSale->products()->updateExistingPivot($item->product_id, [
                            'sold_count' => $flashSaleItem->pivot->sold_count
                        ]);
                    }
                }
            }

            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withError('An error occurred while processing your order: ' . $e->getMessage());
        }
    }

    private function getCart()
    {
        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }
    
    public function cancelOrder(Order $order)
    {       
        // Check if the order is still processing
        if ($order->status === 'processing') {
            $order->update(['status' => 'cancelled']);
            return redirect()->route('orders.index')->with('success', 'Order cancelled successfully.');
        }

        return redirect()->route('orders.index')->with('error', 'Order cannot be cancelled as it is not in processing status.');
    }
}