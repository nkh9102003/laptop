<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 
        'product_id', 
        'quantity', 
        'price',
        'original_price',
        'flash_sale_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
    
    public function product(){
        return $this->belongsTo(Product::class);
    }
    
    public function flashSale(){
        return $this->belongsTo(FlashSale::class);
    }
    
    /**
     * Get the discount percentage if applicable
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->original_price || $this->original_price <= 0 || $this->original_price <= $this->price) {
            return 0;
        }
        
        return round((($this->original_price - $this->price) / $this->original_price) * 100);
    }
    
    /**
     * Check if this item was purchased with a discount
     */
    public function hasDiscount()
    {
        return $this->original_price && $this->price < $this->original_price;
    }
}
