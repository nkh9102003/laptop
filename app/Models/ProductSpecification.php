<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'specification_type_id',
        'value',
    ];

    /**
     * Get the product that owns the specification
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the specification type
     */
    public function specificationType()
    {
        return $this->belongsTo(SpecificationType::class);
    }

    /**
     * Get formatted value with unit if available
     */
    public function getFormattedValueAttribute()
    {
        if ($this->specificationType && $this->specificationType->unit) {
            return $this->value . ' ' . $this->specificationType->unit;
        }
        
        return $this->value;
    }
} 