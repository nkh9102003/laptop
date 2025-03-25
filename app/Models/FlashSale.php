<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(FlashSaleItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'flash_sale_items')
            ->withPivot('discount_price', 'quantity_limit', 'sold_count')
            ->withTimestamps()
            ->using(FlashSaleItem::class);
    }

    public function isActive()
    {
        return $this->is_active && 
               $this->start_time <= now() && 
               $this->end_time >= now();
    }

    public function hasEnded()
    {
        return $this->end_time < now();
    }

    public function hasStarted()
    {
        return $this->start_time <= now();
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'disabled';
        } elseif ($this->start_time <= now() && $this->end_time >= now()) {
            return 'active';
        } elseif ($this->end_time < now()) {
            return 'ended';
        } else {
            return 'upcoming';
        }
    }

    public function getTimeRemainingAttribute()
    {
        if (!$this->isActive()) {
            return null;
        }

        return $this->end_time->diffForHumans();
    }

    public function getStatusDetailsAttribute()
    {
        return [
            'is_active_flag' => (bool)$this->is_active,
            'has_started' => $this->hasStarted(),
            'has_ended' => $this->hasEnded(),
            'current_time' => now()->toDateTimeString(),
            'start_time' => $this->start_time->toDateTimeString(),
            'end_time' => $this->end_time->toDateTimeString(),
            'status' => $this->status
        ];
    }
} 