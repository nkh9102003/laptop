<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpecificationCategory;
use App\Models\SpecificationType;

class LaptopSpecificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define categories with their specification types
        $categories = [
            [
                'name' => 'general',
                'display_name' => 'General',
                'display_order' => 1,
                'types' => [
                    ['name' => 'brand', 'display_name' => 'Brand', 'display_order' => 1],
                    ['name' => 'model', 'display_name' => 'Model', 'display_order' => 2],
                    ['name' => 'series', 'display_name' => 'Series', 'display_order' => 3],
                    ['name' => 'color', 'display_name' => 'Color', 'display_order' => 4],
                    ['name' => 'operating_system', 'display_name' => 'Operating System', 'display_order' => 5],
                ]
            ],
            [
                'name' => 'processor',
                'display_name' => 'Processor',
                'display_order' => 2,
                'types' => [
                    ['name' => 'processor_brand', 'display_name' => 'Brand', 'display_order' => 1],
                    ['name' => 'processor_model', 'display_name' => 'Model', 'display_order' => 2],
                    ['name' => 'processor_generation', 'display_name' => 'Generation', 'display_order' => 3],
                    ['name' => 'processor_cores', 'display_name' => 'Cores', 'display_order' => 4],
                    ['name' => 'processor_base_speed', 'display_name' => 'Base Speed', 'unit' => 'GHz', 'display_order' => 5],
                    ['name' => 'processor_max_speed', 'display_name' => 'Max Turbo Speed', 'unit' => 'GHz', 'display_order' => 6],
                    ['name' => 'processor_cache', 'display_name' => 'Cache', 'unit' => 'MB', 'display_order' => 7],
                ]
            ],
            [
                'name' => 'memory',
                'display_name' => 'Memory (RAM)',
                'display_order' => 3,
                'types' => [
                    ['name' => 'ram_size', 'display_name' => 'Size', 'unit' => 'GB', 'display_order' => 1],
                    ['name' => 'ram_type', 'display_name' => 'Type', 'display_order' => 2],
                    ['name' => 'ram_speed', 'display_name' => 'Speed', 'unit' => 'MHz', 'display_order' => 3],
                    ['name' => 'ram_expandable', 'display_name' => 'Expandable To', 'unit' => 'GB', 'display_order' => 4],
                ]
            ],
            [
                'name' => 'storage',
                'display_name' => 'Storage',
                'display_order' => 4,
                'types' => [
                    ['name' => 'storage_type', 'display_name' => 'Type', 'display_order' => 1],
                    ['name' => 'storage_capacity', 'display_name' => 'Capacity', 'unit' => 'GB', 'display_order' => 2],
                    ['name' => 'storage_interface', 'display_name' => 'Interface', 'display_order' => 3],
                    ['name' => 'storage_rpm', 'display_name' => 'RPM (for HDD)', 'display_order' => 4],
                ]
            ],
            [
                'name' => 'display',
                'display_name' => 'Display',
                'display_order' => 5,
                'types' => [
                    ['name' => 'display_size', 'display_name' => 'Size', 'unit' => 'inches', 'display_order' => 1],
                    ['name' => 'display_resolution', 'display_name' => 'Resolution', 'display_order' => 2],
                    ['name' => 'display_type', 'display_name' => 'Type', 'display_order' => 3],
                    ['name' => 'display_refresh_rate', 'display_name' => 'Refresh Rate', 'unit' => 'Hz', 'display_order' => 4],
                    ['name' => 'display_brightness', 'display_name' => 'Brightness', 'unit' => 'nits', 'display_order' => 5],
                    ['name' => 'display_touchscreen', 'display_name' => 'Touchscreen', 'display_order' => 6],
                ]
            ],
            [
                'name' => 'graphics',
                'display_name' => 'Graphics',
                'display_order' => 6,
                'types' => [
                    ['name' => 'graphics_processor', 'display_name' => 'Processor', 'display_order' => 1],
                    ['name' => 'graphics_memory', 'display_name' => 'Memory', 'unit' => 'GB', 'display_order' => 2],
                    ['name' => 'graphics_type', 'display_name' => 'Type', 'display_order' => 3],
                ]
            ],
            [
                'name' => 'connectivity',
                'display_name' => 'Connectivity',
                'display_order' => 7,
                'types' => [
                    ['name' => 'wifi', 'display_name' => 'Wi-Fi', 'display_order' => 1],
                    ['name' => 'bluetooth', 'display_name' => 'Bluetooth', 'display_order' => 2],
                    ['name' => 'ethernet', 'display_name' => 'Ethernet', 'display_order' => 3],
                ]
            ],
            [
                'name' => 'ports',
                'display_name' => 'Ports & Slots',
                'display_order' => 8,
                'types' => [
                    ['name' => 'usb_ports', 'display_name' => 'USB Ports', 'display_order' => 1],
                    ['name' => 'hdmi_ports', 'display_name' => 'HDMI Ports', 'display_order' => 2],
                    ['name' => 'audio_ports', 'display_name' => 'Audio Ports', 'display_order' => 3],
                    ['name' => 'card_reader', 'display_name' => 'Card Reader', 'display_order' => 4],
                ]
            ],
            [
                'name' => 'battery',
                'display_name' => 'Battery',
                'display_order' => 9,
                'types' => [
                    ['name' => 'battery_type', 'display_name' => 'Type', 'display_order' => 1],
                    ['name' => 'battery_capacity', 'display_name' => 'Capacity', 'unit' => 'Wh', 'display_order' => 2],
                    ['name' => 'battery_life', 'display_name' => 'Battery Life', 'unit' => 'hours', 'display_order' => 3],
                ]
            ],
            [
                'name' => 'physical',
                'display_name' => 'Physical Specifications',
                'display_order' => 10,
                'types' => [
                    ['name' => 'dimensions', 'display_name' => 'Dimensions', 'unit' => 'mm', 'display_order' => 1],
                    ['name' => 'weight', 'display_name' => 'Weight', 'unit' => 'kg', 'display_order' => 2],
                ]
            ],
        ];

        // Create categories and their specification types
        foreach ($categories as $categoryData) {
            $types = $categoryData['types'];
            unset($categoryData['types']);
            
            $category = SpecificationCategory::create($categoryData);
            
            foreach ($types as $typeData) {
                $typeData['category_id'] = $category->id;
                SpecificationType::create($typeData);
            }
        }
    }
} 