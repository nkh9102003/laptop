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
    ];

    /**
     * Get the specifications for this type
     */
    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }
} 