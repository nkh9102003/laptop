<?php

namespace Database\Seeders;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Asus Vivobook X1404ZA i5 1235U (NK376W)',
            'description' => 'Asus Vivobook X1404ZA i5 1235U (NK376W) adalah laptop yang dirancang untuk performa tinggi dan kenyamanan pengguna. Dengan prosesor Intel Core i5-1235U dan grafis Intel Iris Xe Graphics, laptop ini cocok untuk pengguna yang membutuhkan kecepatan dan kinerja optimal.',
            'price' => 1000,
            'stock' => 10,
            'image' => 'Asus Vivobook X1404ZA i5 1235U (NK376W).png',
            'category_id' => 6],
            ['name' => 'HP 15 fd0234TU i5 1334U',
            'description' => 'HP 15 fd0234TU i5 1334U adalah laptop yang dirancang untuk performa tinggi dan kenyamanan pengguna. Dengan prosesor Intel Core i5-1334U dan grafis Intel Iris Xe Graphics, laptop ini cocok untuk pengguna yang membutuhkan kecepatan dan kinerja optimal.',
            'price' => 1000,
            'stock' => 10,
            'image' => 'HP 15 fd0234TU i5 1334U.png',
            'category_id' => 6],
            ['name' => 'Laptop HP Pavilion X360 14-dy0172TU i3 1125G4',
            'description' => 'Laptop HP Pavilion X360 14-dy0172TU i3 1125G4 adalah laptop yang dirancang untuk performa tinggi dan kenyamanan pengguna. Dengan prosesor Intel Core i3-1125G4 dan grafis Intel Iris Xe Graphics, laptop ini cocok untuk pengguna yang membutuhkan kecepatan dan kinerja optimal.',
            'price' => 1000,
            'stock' => 10,
            'image' => 'Laptop HP Pavilion X360 14-dy0172TU i3 1125G4.png',
            'category_id' => 1],
            ['name' => 'Laptop Lenovo Yoga 7 14ACN6 R7 5800U',
            'description' => 'Laptop Lenovo Yoga 7 14ACN6 R7 5800U adalah laptop yang dirancang untuk performa tinggi dan kenyamanan pengguna. Dengan prosesor AMD Ryzen 7 5800U dan grafis AMD Radeon Graphics, laptop ini cocok untuk pengguna yang membutuhkan kecepatan dan kinerja optimal.',
            'price' => 1000,
            'stock' => 10,
            'image' => 'Laptop Lenovo Yoga 7 14ACN6 R7 5800U.png',
            'category_id' => 1],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
        Product::factory()->count(5)->create();
    }
}
