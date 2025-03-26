<?php

namespace App\Models;

use App\Models\Product;
use App\Models\FlashSale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FlashSaleItem extends Pivot
{
    use HasFactory;

    protected $table = 'flash_sale_items';
    
    protected $fillable = [
        'flash_sale_id',
        'product_id',
        'sale_price',
        'max_quantity',
        'sold_count',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'max_quantity' => 'integer',
        'sold_count' => 'integer',
    ];

    // Make sure our accessor is included when the model is converted to an array or JSON
    protected $appends = ['discount_percentage'];

    public $incrementing = true;

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function hasAvailableStock()
    {
        if ($this->max_quantity === null) {
            return true;
        }
        return $this->sold_count < $this->max_quantity;
    }

    public function getDiscountPercentageAttribute()
    {
        return $this->calculateDiscountPercentage();
    }
    
    public function calculateDiscountPercentage()
    {
        // First, make sure we have the necessary data to calculate the discount
        $discountPrice = $this->sale_price;
        
        // Try to get the original price
        $originalPrice = null;
        
        // Method 1: If this is accessed as a pivot, get the parent's price
        if (isset($this->pivotParent) && $this->pivotParent instanceof Product) {
            $originalPrice = $this->pivotParent->price;
        }
        // Method 2: If it's accessed directly and has a product relationship
        else if ($this->product) {
            $originalPrice = $this->product->price;
        }
        
        // Skip calculation if we can't find the original price or it's zero/negative
        if (!$originalPrice || $originalPrice <= 0) {
            return 0;
        }
        
        // Calculate and return the percentage
        $discount = $originalPrice - $discountPrice;
        $percentage = round(($discount / $originalPrice) * 100);
        
        return $percentage;
    }

} 