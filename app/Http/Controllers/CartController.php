<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\FlashSale;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        return view('cart.index', compact('cart'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $cart = $this->getCart();
        $quantity = $request->input('quantity', 1);
        
        // Check if this is a flash sale product
        $flashSaleId = $request->input('flash_sale_id');
        $flashSalePrice = null;
        
        if ($flashSaleId) {
            $flashSale = FlashSale::with('products')->findOrFail($flashSaleId);
            
            // Only apply flash sale price if the sale is active
            if ($flashSale->isActive()) {
                $flashSaleItem = $flashSale->products()
                    ->where('product_id', $product->id)
                    ->first();
                
                if ($flashSaleItem) {
                    $flashSalePrice = $flashSaleItem->pivot->sale_price;
                    
                    // Check if quantity limit is reached
                    if ($flashSaleItem->pivot->max_quantity) {
                        $remainingQuantity = $flashSaleItem->pivot->max_quantity - $flashSaleItem->pivot->sold_count;
                        
                        if ($remainingQuantity <= 0) {
                            return redirect()->back()->with('error', 'This flash sale item is sold out!');
                        }
                        
                        // Adjust quantity if it exceeds remaining items
                        if ($quantity > $remainingQuantity) {
                            $quantity = $remainingQuantity;
                        }
                        
                        // Update the sold count
                        $flashSale->products()->updateExistingPivot($product->id, [
                            'sold_count' => $flashSaleItem->pivot->sold_count + $quantity
                        ]);
                    }
                }
            }
        }
        
        // Check if the product is already in the cart
        $existingItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($existingItem) {
            // If already in cart, increase quantity
            $existingItem->quantity += $quantity;
            
            // Update price if it's a flash sale item
            if ($flashSalePrice && (!$existingItem->flash_sale_price || $flashSalePrice < $existingItem->flash_sale_price)) {
                $existingItem->flash_sale_price = $flashSalePrice;
                $existingItem->flash_sale_id = $flashSaleId;
            }
            
            $existingItem->save();
        } else {
            // Create a new cart item
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'flash_sale_price' => $flashSalePrice,
                'flash_sale_id' => $flashSaleId,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    private function getCart()
    {
        $sessionId = session()->getId();
        $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        return $cart;
    }
    
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // If this is a flash sale item, check quantity limits
        if ($cartItem->flash_sale_id) {
            $flashSale = FlashSale::findOrFail($cartItem->flash_sale_id);
            $flashSaleItem = $flashSale->products()->where('product_id', $cartItem->product_id)->first();
            
            if ($flashSaleItem && $flashSaleItem->pivot->max_quantity) {
                // Calculate how many more items can be added (exclude current cart quantity)
                $remainingQuantity = $flashSaleItem->pivot->max_quantity - 
                    ($flashSaleItem->pivot->sold_count - $cartItem->quantity);
                
                if ($request->quantity > $remainingQuantity) {
                    return redirect()->route('cart.index')
                        ->with('error', "Only {$remainingQuantity} items remaining for this flash sale product!");
                }
                
                // Update sold count in the flash sale
                $soldCountChange = $request->quantity - $cartItem->quantity;
                $flashSale->products()->updateExistingPivot($cartItem->product_id, [
                    'sold_count' => $flashSaleItem->pivot->sold_count + $soldCountChange
                ]);
            }
        }
        
        $cartItem->update(['quantity' => $request->quantity]);
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully');
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // If this is a flash sale item, update the sold count when removing from cart
        if ($cartItem->flash_sale_id) {
            $flashSale = FlashSale::findOrFail($cartItem->flash_sale_id);
            $flashSaleItem = $flashSale->products()->where('product_id', $cartItem->product_id)->first();
            
            if ($flashSaleItem) {
                // Decrease the sold count
                $newSoldCount = max(0, $flashSaleItem->pivot->sold_count - $cartItem->quantity);
                $flashSale->products()->updateExistingPivot($cartItem->product_id, [
                    'sold_count' => $newSoldCount
                ]);
            }
        }
        
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
    
    public function clear()
    {
        $cart = $this->getCart();
        
        // Update flash sale sold counts for all cart items
        foreach ($cart->items as $item) {
            if ($item->flash_sale_id) {
                $flashSale = FlashSale::findOrFail($item->flash_sale_id);
                $flashSaleItem = $flashSale->products()->where('product_id', $item->product_id)->first();
                
                if ($flashSaleItem) {
                    // Decrease the sold count
                    $newSoldCount = max(0, $flashSaleItem->pivot->sold_count - $item->quantity);
                    $flashSale->products()->updateExistingPivot($item->product_id, [
                        'sold_count' => $newSoldCount
                    ]);
                }
            }
        }
        
        $cart->items()->delete();
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully');
    }
}