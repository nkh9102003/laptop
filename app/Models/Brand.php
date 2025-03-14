<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'logo'];
    
    protected $appends = ['logo_url'];

    /**
     * Get the path where brand logos are stored
     */
    protected function getLogoPath()
    {
        return Config::get('filesystems.paths.brand_logos', 'images/brands/');
    }

    /**
     * Get the brand's logo URL.
     *
     * @return string
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return asset('images/no-image.jpg');
        }
        
        return asset($this->getLogoPath() . $this->logo);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}