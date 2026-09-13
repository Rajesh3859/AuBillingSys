<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SparePart;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AutomobileSparesSeeder extends Seeder
{
    public function run(): void
    {
        // Seed default admin user
        User::firstOrCreate(
            ['email' => 'admin@autospares.com'],
            [
                'name' => 'Store Administrator',
                'password' => bcrypt('password'),
            ]
        );

        // Seed Categories
        $categories = [
            ['name' => 'Braking System', 'slug' => 'braking-system', 'description' => 'Brake pads, rotors, shoes, master cylinders, and fluids.'],
            ['name' => 'Engine & Transmission', 'slug' => 'engine-transmission', 'description' => 'Oil filters, spark plugs, timing belts, clutch assemblies.'],
            ['name' => 'Suspension & Steering', 'slug' => 'suspension-steering', 'description' => 'Shock absorbers, tie rods, ball joints, control arms.'],
            ['name' => 'Electrical & Lighting', 'slug' => 'electrical-lighting', 'description' => 'Headlamps, tail lights, alternators, starter motors, fuses.'],
            ['name' => 'Filters & Lubricants', 'slug' => 'filters-lubricants', 'description' => 'Engine oil, cabin air filters, transmission fluids.'],
            ['name' => 'Body & Accessories', 'slug' => 'body-accessories', 'description' => 'Wiper blades, mirrors, door handles, bumpers.'],
        ];

        $categoryModels = [];
        foreach ($categories as $catData) {
            $categoryModels[$catData['slug']] = Category::create($catData);
        }

        // Seed Spare Parts
        $parts = [
            [
                'part_number' => 'BP-HYU-001',
                'name' => 'Front Ceramic Brake Pads',
                'category_id' => $categoryModels['braking-system']->id,
                'brand' => 'Bosch',
                'compatible_models' => 'Hyundai i20, Verna, Creta (2018-2023)',
                'unit_price' => 2450.00,
                'cost_price' => 1750.00,
                'stock_quantity' => 24,
                'reorder_level' => 5,
                'rack_location' => 'Rack A-12',
            ],
            [
                'part_number' => 'OF-MAR-102',
                'name' => 'High-Performance Engine Oil Filter',
                'category_id' => $categoryModels['engine-transmission']->id,
                'brand' => 'MGP (Maruti)',
                'compatible_models' => 'Maruti Swift, Dzire, Baleno 1.2L',
                'unit_price' => 380.00,
                'cost_price' => 250.00,
                'stock_quantity' => 50,
                'reorder_level' => 10,
                'rack_location' => 'Rack B-04',
            ],
            [
                'part_number' => 'SP-NGK-708',
                'name' => 'Iridium IX Spark Plug Set (Pack of 4)',
                'category_id' => $categoryModels['engine-transmission']->id,
                'brand' => 'NGK',
                'compatible_models' => 'Honda City, Civic, Jazz',
                'unit_price' => 3200.00,
                'cost_price' => 2400.00,
                'stock_quantity' => 15,
                'reorder_level' => 4,
                'rack_location' => 'Rack B-08',
            ],
            [
                'part_number' => 'SA-MON-441',
                'name' => 'Gas Magnum Rear Shock Absorber Pair',
                'category_id' => $categoryModels['suspension-steering']->id,
                'brand' => 'Monroe',
                'compatible_models' => 'Toyota Innova Crysta / Fortuner',
                'unit_price' => 8900.00,
                'cost_price' => 6800.00,
                'stock_quantity' => 3, // LOW STOCK TRIGGER
                'reorder_level' => 5,
                'rack_location' => 'Rack S-01',
            ],
            [
                'part_number' => 'HL-PHL-900',
                'name' => 'X-tremeVision Pro150 H7 Headlamp Bulb (Pair)',
                'category_id' => $categoryModels['electrical-lighting']->id,
                'brand' => 'Philips',
                'compatible_models' => 'Universal H7 Fitting (Mahindra, Tata, VW)',
                'unit_price' => 1850.00,
                'cost_price' => 1250.00,
                'stock_quantity' => 30,
                'reorder_level' => 8,
                'rack_location' => 'Rack E-02',
            ],
            [
                'part_number' => 'TB-CON-305',
                'name' => 'Timing Belt Kit with Water Pump',
                'category_id' => $categoryModels['engine-transmission']->id,
                'brand' => 'Continental',
                'compatible_models' => 'Volkswagen Polo 1.2 TDI, Vento, Rapid',
                'unit_price' => 7400.00,
                'cost_price' => 5500.00,
                'stock_quantity' => 2, // LOW STOCK TRIGGER
                'reorder_level' => 4,
                'rack_location' => 'Rack B-15',
            ],
            [
                'part_number' => 'CP-LUK-220',
                'name' => 'Clutch Plate & Pressure Plate Assembly',
                'category_id' => $categoryModels['engine-transmission']->id,
                'brand' => 'LuK',
                'compatible_models' => 'Tata Nexon 1.5 D, Altroz',
                'unit_price' => 6850.00,
                'cost_price' => 5100.00,
                'stock_quantity' => 8,
                'reorder_level' => 3,
                'rack_location' => 'Rack C-05',
            ],
            [
                'part_number' => 'EO-CST-5W30',
                'name' => 'Castrol EDGE 5W-30 Synthetic Oil (3.5L)',
                'category_id' => $categoryModels['filters-lubricants']->id,
                'brand' => 'Castrol',
                'compatible_models' => 'All Petrol & Turbo Diesel Passenger Cars',
                'unit_price' => 2950.00,
                'cost_price' => 2100.00,
                'stock_quantity' => 40,
                'reorder_level' => 8,
                'rack_location' => 'Rack L-01',
            ],
        ];

        $spareModels = [];
        foreach ($parts as $partData) {
            $spareModels[] = SparePart::create($partData);
        }

        // Seed Customers
        $customers = [
            [
                'name' => 'Rajesh Sharma',
                'phone' => '9876543210',
                'email' => 'rajesh.sharma@example.com',
                'vehicle_number' => 'KA-01-MJ-4589',
                'gst_number' => '29ABCDE1234F1Z5',
                'address' => '45 Indiranagar 100ft Road, Bengaluru',
            ],
            [
                'name' => 'Priya Verma',
                'phone' => '9812345678',
                'email' => 'priya.v@example.com',
                'vehicle_number' => 'MH-12-PQ-9012',
                'gst_number' => null,
                'address' => 'Plot 88 Kothrud, Pune',
            ],
            [
                'name' => 'Apex Auto Care Garage',
                'phone' => '9944332211',
                'email' => 'contact@apexautocare.com',
                'vehicle_number' => 'Commercial Account',
                'gst_number' => '33XYZAB9876C1Z2',
                'address' => 'Industrial Estate Zone 4, Chennai',
            ],
        ];

        $customerModels = [];
        foreach ($customers as $custData) {
            $customerModels[] = Customer::create($custData);
        }

        // Seed Invoices & Items
        $inv1 = Invoice::create([
            'invoice_number' => 'INV-2026-0001',
            'customer_id' => $customerModels[0]->id,
            'customer_name' => $customerModels[0]->name,
            'customer_phone' => $customerModels[0]->phone,
            'vehicle_number' => $customerModels[0]->vehicle_number,
            'invoice_date' => now()->subDays(2),
            'subtotal' => 5650.00,
            'tax_amount' => 1017.00, // 18%
            'discount' => 200.00,
            'total_amount' => 6467.00,
            'payment_status' => 'paid',
            'payment_method' => 'upi',
            'notes' => 'General service spares replacement.',
        ]);

        InvoiceItem::create([
            'invoice_id' => $inv1->id,
            'spare_part_id' => $spareModels[0]->id,
            'part_number' => $spareModels[0]->part_number,
            'part_name' => $spareModels[0]->name,
            'quantity' => 1,
            'unit_price' => $spareModels[0]->unit_price,
            'tax_rate' => 18.00,
            'total_price' => 2450.00,
        ]);

        InvoiceItem::create([
            'invoice_id' => $inv1->id,
            'spare_part_id' => $spareModels[7]->id,
            'part_number' => $spareModels[7]->part_number,
            'part_name' => $spareModels[7]->name,
            'quantity' => 1,
            'unit_price' => $spareModels[7]->unit_price,
            'tax_rate' => 18.00,
            'total_price' => 2950.00,
        ]);

        $inv2 = Invoice::create([
            'invoice_number' => 'INV-2026-0002',
            'customer_id' => $customerModels[2]->id,
            'customer_name' => $customerModels[2]->name,
            'customer_phone' => $customerModels[2]->phone,
            'vehicle_number' => $customerModels[2]->vehicle_number,
            'invoice_date' => now()->subHours(5),
            'subtotal' => 15750.00,
            'tax_amount' => 2835.00,
            'discount' => 500.00,
            'total_amount' => 18085.00,
            'payment_status' => 'paid',
            'payment_method' => 'card',
            'notes' => 'Bulk supply to Apex Auto Care.',
        ]);

        InvoiceItem::create([
            'invoice_id' => $inv2->id,
            'spare_part_id' => $spareModels[3]->id,
            'part_number' => $spareModels[3]->part_number,
            'part_name' => $spareModels[3]->name,
            'quantity' => 1,
            'unit_price' => $spareModels[3]->unit_price,
            'tax_rate' => 18.00,
            'total_price' => 8900.00,
        ]);

        InvoiceItem::create([
            'invoice_id' => $inv2->id,
            'spare_part_id' => $spareModels[6]->id,
            'part_number' => $spareModels[6]->part_number,
            'part_name' => $spareModels[6]->name,
            'quantity' => 1,
            'unit_price' => $spareModels[6]->unit_price,
            'tax_rate' => 18.00,
            'total_price' => 6850.00,
        ]);
    }
}
