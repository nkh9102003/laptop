<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Product extends Model
{
    use HasFactory;

    /**
     * The path where product images are stored
     */
    protected $imagePath = 'images/products/';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'brand_id',
    ];

    protected $appends = ['image_url'];

    /**
     * Get the path where product images are stored
     */
    protected function getImagePath()
    {
        return Config::get('filesystems.paths.product_images', 'images/products/');
    }

    /**
     * Get the product's image URL.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/no-image.jpg');
        }
        
        return asset($this->getImagePath() . $this->image);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Get the specifications for this product
     */
    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }
    
    /**
     * Get a specific specification value by specification type name
     * 
     * @param string $typeName
     * @return string|null
     */
    public function getSpecificationValue($typeName)
    {
        $spec = $this->specifications()
            ->whereHas('specificationType', function($query) use ($typeName) {
                $query->where('name', $typeName);
            })
            ->first();
            
        return $spec ? $spec->formatted_value : null;
    }
}
