<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cart_id', 
        'product_id', 
        'quantity', 
        'flash_sale_id', 
        'flash_sale_price'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }
    
    /**
     * Get the effective price for this cart item (flash sale price or regular price)
     */
    public function getEffectivePriceAttribute()
    {
        if ($this->flash_sale_price && $this->flashSale && $this->flashSale->isActive()) {
            return $this->flash_sale_price;
        }
        
        return $this->product ? $this->product->price : 0;
    }
    
    /**
     * Get the total price for this cart item
     */
    public function getTotalAttribute()
    {
        return $this->effective_price * $this->quantity;
    }
    
    /**
     * Get the discount percentage if applicable
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->product || !$this->flash_sale_price || !$this->flashSale || !$this->flashSale->isActive()) {
            return 0;
        }
        
        return round((($this->product->price - $this->flash_sale_price) / $this->product->price) * 100);
    }
    
    /**
     * Determine if this cart item has a valid flash sale discount
     */
    public function hasActiveDiscount()
    {
        return $this->flash_sale_price && 
               $this->flashSale && 
               $this->flashSale->isActive() && 
               $this->flash_sale_price < $this->product->price;
    }
}
