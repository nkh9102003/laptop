<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'unit',
        'description',
        'category_id',
        'display_order',
    ];

    /**
     * Get the specifications for this type
     */
    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }
    
    /**
     * Get the category for this specification type
     */
    public function category()
    {
        return $this->belongsTo(SpecificationCategory::class, 'category_id');
    }
} 