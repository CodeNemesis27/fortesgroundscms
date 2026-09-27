<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = Vendor::pluck('id', 'vendor_code');

        $materials = [
            // ==========================================
            // Concrete and Cement Products
            // ==========================================
            [
                'name' => 'Portland Cement 40kg',
                'category' => 'Concrete and Cement Products',
                'unit' => 'bag',
                'price' => 285,
                'vendor_id' => $vendors['V-001'],
            ],
            [
                'name' => 'Type 1 Portland Cement 40kg',
                'category' => 'Concrete and Cement Products',
                'unit' => 'bag',
                'price' => 295,
                'vendor_id' => $vendors['V-004'],
            ],
            [
                'name' => 'Concrete Hollow Block 4"',
                'category' => 'Concrete and Cement Products',
                'unit' => 'piece',
                'price' => 18,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Concrete Hollow Block 6"',
                'category' => 'Concrete and Cement Products',
                'unit' => 'piece',
                'price' => 24,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Ready-Mix Concrete 3000 PSI',
                'category' => 'Concrete and Cement Products',
                'unit' => 'm³',
                'price' => 5200,
                'vendor_id' => $vendors['V-001'],
            ],
            [
                'name' => 'Ready-Mix Concrete 4000 PSI',
                'category' => 'Concrete and Cement Products',
                'unit' => 'm³',
                'price' => 5700,
                'vendor_id' => $vendors['V-001'],
            ],
            [
                'name' => 'Concrete Paving Block 60mm',
                'category' => 'Concrete and Cement Products',
                'unit' => 'piece',
                'price' => 28,
                'vendor_id' => $vendors['V-002'],
            ],

            // ==========================================
            // Doors, Windows and Glazing
            // ==========================================
            [
                'name' => 'Aluminum Sliding Window 4ft x 4ft',
                'category' => 'Doors, Windows and Glazing',
                'unit' => 'piece',
                'price' => 4850,
                'vendor_id' => $vendors['V-003'],
            ],
            [
                'name' => 'Aluminum Sliding Door 6ft x 7ft',
                'category' => 'Doors, Windows and Glazing',
                'unit' => 'piece',
                'price' => 12500,
                'vendor_id' => $vendors['V-003'],
            ],
            [
                'name' => 'Clear Glass 6mm',
                'category' => 'Doors, Windows and Glazing',
                'unit' => 'sq.m',
                'price' => 1850,
                'vendor_id' => $vendors['V-003'],
            ],
            [
                'name' => 'Steel Door 900mm x 2100mm',
                'category' => 'Doors, Windows and Glazing',
                'unit' => 'piece',
                'price' => 8500,
                'vendor_id' => $vendors['V-003'],
            ],
            [
                'name' => 'Flush Door 900mm x 2100mm',
                'category' => 'Doors, Windows and Glazing',
                'unit' => 'piece',
                'price' => 4200,
                'vendor_id' => $vendors['V-003'],
            ],
            [
                'name' => 'Tempered Glass 10mm',
                'category' => 'Doors, Windows and Glazing',
                'unit' => 'sq.m',
                'price' => 3200,
                'vendor_id' => $vendors['V-003'],
            ],

            // ==========================================
            // Electrical Materials
            // ==========================================
            [
                'name' => 'THHN Copper Wire 2.0mm²',
                'category' => 'Electrical Materials',
                'unit' => 'meter',
                'price' => 38,
                'vendor_id' => $vendors['V-006'],
            ],
            [
                'name' => 'THHN Copper Wire 3.5mm²',
                'category' => 'Electrical Materials',
                'unit' => 'meter',
                'price' => 62,
                'vendor_id' => $vendors['V-006'],
            ],
            [
                'name' => 'THHN Copper Wire 5.5mm²',
                'category' => 'Electrical Materials',
                'unit' => 'meter',
                'price' => 95,
                'vendor_id' => $vendors['V-006'],
            ],
            [
                'name' => 'PVC Electrical Conduit 20mm',
                'category' => 'Electrical Materials',
                'unit' => 'length',
                'price' => 95,
                'vendor_id' => $vendors['V-006'],
            ],
            [
                'name' => '20A Circuit Breaker',
                'category' => 'Electrical Materials',
                'unit' => 'piece',
                'price' => 385,
                'vendor_id' => $vendors['V-006'],
            ],
            [
                'name' => '30A Circuit Breaker',
                'category' => 'Electrical Materials',
                'unit' => 'piece',
                'price' => 425,
                'vendor_id' => $vendors['V-006'],
            ],
            [
                'name' => 'LED Downlight 12W',
                'category' => 'Electrical Materials',
                'unit' => 'piece',
                'price' => 280,
                'vendor_id' => $vendors['V-006'],
            ],

            // ==========================================
            // FINISHES
            // ==========================================
            [
                'name' => 'Interior Latex Paint 4L',
                'category' => 'Finishes',
                'unit' => 'pail',
                'price' => 1450,
                'vendor_id' => $vendors['V-001'],
            ],
            [
                'name' => 'Exterior Acrylic Paint 4L',
                'category' => 'Finishes',
                'unit' => 'pail',
                'price' => 1680,
                'vendor_id' => $vendors['V-001'],
            ],
            [
                'name' => 'Ceramic Floor Tile 40x40cm',
                'category' => 'Finishes',
                'unit' => 'box',
                'price' => 850,
                'vendor_id' => $vendors['V-001'],
            ],
            [
                'name' => 'Tile Adhesive 25kg',
                'category' => 'Finishes',
                'unit' => 'bag',
                'price' => 485,
                'vendor_id' => $vendors['V-001'],
            ],
            [
                'name' => 'Wall Putty 20kg',
                'category' => 'Finishes',
                'unit' => 'pail',
                'price' => 680,
                'vendor_id' => $vendors['V-001'],
            ],
            [
                'name' => 'Floor Tile Grout 2kg',
                'category' => 'Finishes',
                'unit' => 'bag',
                'price' => 185,
                'vendor_id' => $vendors['V-001'],
            ],

            // ==========================================
            // Lumber and Carpentry
            // ==========================================
            [
                'name' => '2x2 Kiln-Dried Lumber',
                'category' => 'Lumber and Carpentry',
                'unit' => 'piece',
                'price' => 185,
                'vendor_id' => $vendors['V-005'],
            ],
            [
                'name' => '2x4 Kiln-Dried Lumber',
                'category' => 'Lumber and Carpentry',
                'unit' => 'piece',
                'price' => 320,
                'vendor_id' => $vendors['V-005'],
            ],
            [
                'name' => '2x6 Kiln-Dried Lumber',
                'category' => 'Lumber and Carpentry',
                'unit' => 'piece',
                'price' => 480,
                'vendor_id' => $vendors['V-005'],
            ],
            [
                'name' => 'Marine Plywood 1/2"',
                'category' => 'Lumber and Carpentry',
                'unit' => 'sheet',
                'price' => 1450,
                'vendor_id' => $vendors['V-005'],
            ],
            [
                'name' => 'Ordinary Plywood 1/4"',
                'category' => 'Lumber and Carpentry',
                'unit' => 'sheet',
                'price' => 780,
                'vendor_id' => $vendors['V-005'],
            ],
            [
                'name' => 'Phenolic Board 1/2"',
                'category' => 'Lumber and Carpentry',
                'unit' => 'sheet',
                'price' => 1650,
                'vendor_id' => $vendors['V-005'],
            ],

            // ==========================================
            // Masonry and Aggregates
            // ==========================================
            [
                'name' => 'Washed Sand',
                'category' => 'Masonry and Aggregates',
                'unit' => 'm³',
                'price' => 1450,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Crushed Gravel 3/4"',
                'category' => 'Masonry and Aggregates',
                'unit' => 'm³',
                'price' => 1750,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Crushed Gravel 1/2"',
                'category' => 'Masonry and Aggregates',
                'unit' => 'm³',
                'price' => 1800,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Fine Aggregates',
                'category' => 'Masonry and Aggregates',
                'unit' => 'm³',
                'price' => 1350,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Concrete Sand',
                'category' => 'Masonry and Aggregates',
                'unit' => 'm³',
                'price' => 1500,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Gravel Base Course',
                'category' => 'Masonry and Aggregates',
                'unit' => 'm³',
                'price' => 1950,
                'vendor_id' => $vendors['V-002'],
            ],

            // ==========================================
            // Plumbing Materials
            // ==========================================
            [
                'name' => 'PVC Pipe 1/2" x 3m',
                'category' => 'Plumbing Materials',
                'unit' => 'length',
                'price' => 145,
                'vendor_id' => $vendors['V-007'],
            ],
            [
                'name' => 'PVC Pipe 1" x 3m',
                'category' => 'Plumbing Materials',
                'unit' => 'length',
                'price' => 285,
                'vendor_id' => $vendors['V-007'],
            ],
            [
                'name' => 'PVC Pipe 2" x 3m',
                'category' => 'Plumbing Materials',
                'unit' => 'length',
                'price' => 520,
                'vendor_id' => $vendors['V-007'],
            ],
            [
                'name' => 'PVC Elbow 1/2"',
                'category' => 'Plumbing Materials',
                'unit' => 'piece',
                'price' => 28,
                'vendor_id' => $vendors['V-007'],
            ],
            [
                'name' => 'PVC Ball Valve 1"',
                'category' => 'Plumbing Materials',
                'unit' => 'piece',
                'price' => 185,
                'vendor_id' => $vendors['V-007'],
            ],
            [
                'name' => 'PVC Tee 1"',
                'category' => 'Plumbing Materials',
                'unit' => 'piece',
                'price' => 45,
                'vendor_id' => $vendors['V-007'],
            ],

            // ==========================================
            // Roofing and Waterproofing
            // ==========================================
            [
                'name' => 'Pre-Painted Long Span Roofing 0.50mm',
                'category' => 'Roofing and Waterproofing',
                'unit' => 'sheet',
                'price' => 1250,
                'vendor_id' => $vendors['V-003'],
            ],
            [
                'name' => 'Pre-Painted Long Span Roofing 0.60mm',
                'category' => 'Roofing and Waterproofing',
                'unit' => 'sheet',
                'price' => 1480,
                'vendor_id' => $vendors['V-003'],
            ],
            [
                'name' => 'Roofing Screw 2"',
                'category' => 'Roofing and Waterproofing',
                'unit' => 'box',
                'price' => 385,
                'vendor_id' => $vendors['V-003'],
            ],
            [
                'name' => 'Cementitious Waterproofing 20kg',
                'category' => 'Roofing and Waterproofing',
                'unit' => 'bag',
                'price' => 1250,
                'vendor_id' => $vendors['V-004'],
            ],
            [
                'name' => 'Roof Flashing 0.50mm',
                'category' => 'Roofing and Waterproofing',
                'unit' => 'meter',
                'price' => 320,
                'vendor_id' => $vendors['V-003'],
            ],

            // ==========================================
            // Site and Earthworks
            // ==========================================
            [
                'name' => 'Filling Materials',
                'category' => 'Site and Earthworks',
                'unit' => 'm³',
                'price' => 850,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Selected Backfill Materials',
                'category' => 'Site and Earthworks',
                'unit' => 'm³',
                'price' => 1100,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Common Backfill',
                'category' => 'Site and Earthworks',
                'unit' => 'm³',
                'price' => 750,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Aggregate Base Course',
                'category' => 'Site and Earthworks',
                'unit' => 'm³',
                'price' => 1950,
                'vendor_id' => $vendors['V-002'],
            ],
            [
                'name' => 'Geotextile Fabric',
                'category' => 'Site and Earthworks',
                'unit' => 'sq.m',
                'price' => 85,
                'vendor_id' => $vendors['V-002'],
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}
