<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'display_order',
    ];

    /**
     * Get the specification types for this category
     */
    public function specificationTypes()
    {
        return $this->hasMany(SpecificationType::class, 'category_id')
            ->orderBy('display_order');
    }
    
    /**
     * Get all product specifications for this category
     */
    public function productSpecifications()
    {
        return $this->hasManyThrough(
            ProductSpecification::class,
            SpecificationType::class,
            'category_id', // Foreign key on specification_types table
            'specification_type_id', // Foreign key on product_specifications table
            'id', // Local key on specification_categories table
            'id' // Local key on specification_types table
        );
    }
} 