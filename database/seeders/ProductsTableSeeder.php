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
            [
                'name' => 'Samsung Galaxy Book Pro 360 15"',
                'description' => 'Premium 2-in-1 laptop featuring a stunning AMOLED display, S Pen support, and powerful performance in an ultra-thin design. Perfect for creative professionals and multitaskers.',
                'price' => 1399.99,
                'stock' => 15,
                'image' => 'samsung-galaxy-book-pro-360-15-np954qed-us-1729224602.jpg',
                'brand_id' => 1
            ],
            [
                'name' => 'Samsung Galaxy Book 3 360 15"',
                'description' => 'Versatile 2-in-1 laptop with a brilliant touchscreen display, enhanced S Pen functionality, and powerful Intel processor for seamless productivity and entertainment.',
                'price' => 1299.99,
                'stock' => 20,
                'image' => 'samsung-galaxy-book-3-360-15-np750qfg-us-1729224522.jpg',
                'brand_id' => 1
            ],
            [
                'name' => 'Samsung Galaxy Book 13"',
                'description' => 'Ultra-portable laptop with a crisp display, long battery life, and efficient performance for everyday computing needs.',
                'price' => 899.99,
                'stock' => 25,
                'image' => 'samsung-galaxy-book-13-np754xfg-us-1729224347.jpg',
                'brand_id' => 1
            ],
            [
                'name' => 'Samsung Galaxy Book 4 15"',
                'description' => 'Latest generation laptop featuring cutting-edge technology, enhanced performance, and a premium design for both work and entertainment.',
                'price' => 999.99,
                'stock' => 18,
                'image' => 'samsung-galaxy-book-4-15-np750xgk-us-1729224310.jpg',
                'brand_id' => 1
            ],
            [
                'name' => 'Apple MacBook Air 15" M3',
                'description' => 'Powered by the revolutionary M3 chip, this MacBook Air offers exceptional performance, incredible battery life, and a gorgeous Retina display in a thin and light design.',
                'price' => 1299.99,
                'stock' => 12,
                'image' => 'apple-macbook-air-15-m3-us-1729224179.jpg',
                'brand_id' => 2
            ],
            [
                'name' => 'Apple MacBook Pro 14" M3',
                'description' => 'Professional-grade laptop featuring the powerful M3 chip, stunning Liquid Retina XDR display, and pro-level performance for demanding creative work.',
                'price' => 1999.99,
                'stock' => 10,
                'image' => 'apple-macbook-pro-14-m3-us-1729224041.jpg',
                'brand_id' => 2
            ],
            [
                'name' => 'ASUS ZenBook 14 UX3402ZA',
                'description' => 'Premium ultrabook with a gorgeous OLED display, powerful Intel processor, and all-day battery life in an elegant design.',
                'price' => 1099.99,
                'stock' => 22,
                'image' => 'asus-zenbook-14-ux3402za-us-1729223966.jpg',
                'brand_id' => 3
            ],
            [
                'name' => 'ASUS VivoBook S 15 Q5507QA',
                'description' => 'Stylish and powerful laptop with AMD Ryzen processor, delivering excellent performance for everyday tasks and light gaming.',
                'price' => 799.99,
                'stock' => 30,
                'image' => 'asus-vivobook-s-15-q5507qa-us-1729223912.jpg',
                'brand_id' => 3
            ],
            [
                'name' => 'ASUS VivoBook 15 F515EA',
                'description' => 'Value-focused laptop offering reliable performance, comfortable typing experience, and essential features for daily computing.',
                'price' => 599.99,
                'stock' => 35,
                'image' => 'asus-vivobook-15-f515ea-us-1729223821.jpg',
                'brand_id' => 3
            ],
            [
                'name' => 'ASUS VivoBook S15 K3502ZA',
                'description' => 'Modern laptop combining style with substance, featuring a large display, latest Intel processors, and premium design elements.',
                'price' => 899.99,
                'stock' => 25,
                'image' => 'asus-vivobook-s15-k3502za-us-1729223768.jpg',
                'brand_id' => 3
            ],
            [
                'name' => 'Dell Vostro 15 3530',
                'description' => 'Business-focused laptop offering reliability, security features, and strong performance for professional users.',
                'price' => 799.99,
                'stock' => 20,
                'image' => 'dell-vostro-15-3530-us-1729223699.jpg',
                'brand_id' => 4
            ],
            [
                'name' => 'Dell Inspiron 16 5645',
                'description' => 'Large-screen laptop with powerful performance, immersive display, and premium features for both work and entertainment.',
                'price' => 899.99,
                'stock' => 15,
                'image' => 'dell-inspiron-16-5645-us-1729223585.jpg',
                'brand_id' => 4
            ],
            [
                'name' => 'Dell Inspiron 15 3520',
                'description' => 'Reliable everyday laptop offering good performance, comfortable keyboard, and essential features at an affordable price.',
                'price' => 599.99,
                'stock' => 28,
                'image' => 'dell-inspiron-15-3520-us-1729223392.jpg',
                'brand_id' => 4
            ],
            [
                'name' => 'Dell Inspiron 14 2-in-1 7440',
                'description' => 'Versatile convertible laptop with touchscreen display, powerful performance, and flexible design for various use cases.',
                'price' => 999.99,
                'stock' => 18,
                'image' => 'dell-inspiron-14-2-in-1-7440-us-1729223321.jpg',
                'brand_id' => 4
            ],
            [
                'name' => 'Dell Latitude 14 5420',
                'description' => 'Business-class laptop featuring enterprise-grade security, reliable performance, and durability for professional use.',
                'price' => 1199.99,
                'stock' => 15,
                'image' => 'dell-latitude-14-5420-us-1729223311.jpg',
                'brand_id' => 4
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
