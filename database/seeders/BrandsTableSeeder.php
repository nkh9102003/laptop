<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandsTableSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            [
                'name' => 'Samsung',
                'description' => 'South Korean multinational electronics company known for innovative laptops and mobile devices.',
            ],
            [
                'name' => 'Apple',
                'description' => 'American technology company known for premium laptops with macOS and exceptional build quality.',
            ],
            [
                'name' => 'ASUS',
                'description' => 'Taiwanese multinational computer and electronics company offering a wide range of laptops for different needs.',
            ],
            [
                'name' => 'Dell',
                'description' => 'American technology company providing reliable business and consumer laptops with excellent support.',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
} 