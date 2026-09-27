<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'vendor_code' => 'V-001',
                'name' => 'Davao Prime Construction Supply',
                'contact_person' => 'Mark Anthony Reyes',
                'contact_no' => '09172458136',
                'email' => 'davaoprime@gmail.com',
                'address' => 'J.P. Laurel Avenue, Bajada, Davao City, Davao del Sur',
                'vendor_type' => 'Material supplier',
            ],
            [
                'vendor_code' => 'V-002',
                'name' => 'Mindanao Builders Depot',
                'contact_person' => 'Ramon dela Cruz',
                'contact_no' => '09205187429',
                'email' => 'mindanaobuilders@gmail.com',
                'address' => 'McArthur Highway, Matina, Davao City, Davao del Sur',
                'vendor_type' => 'Material supplier',
            ],
            [
                'vendor_code' => 'V-003',
                'name' => 'Southern Steel and Hardware Supply',
                'contact_person' => 'Carlo Mendoza',
                'contact_no' => '09186342751',
                'email' => 'southernsteel@gmail.com',
                'address' => 'Diversion Road, Buhangin, Davao City, Davao del Sur',
                'vendor_type' => 'Material supplier',
            ],
            [
                'vendor_code' => 'V-004',
                'name' => 'Island Cement Trading',
                'contact_person' => 'Jennifer Santos',
                'contact_no' => '09278413652',
                'email' => 'islandcement@gmail.com',
                'address' => 'Sasa Wharf Road, Sasa, Davao City, Davao del Sur',
                'vendor_type' => 'Material supplier',
            ],
            [
                'vendor_code' => 'V-005',
                'name' => 'Philippine Lumber and Plywood Center',
                'contact_person' => 'Eduardo Garcia',
                'contact_no' => '09197254836',
                'email' => 'phillumber@gmail.com',
                'address' => 'Toril District, Davao City, Davao del Sur',
                'vendor_type' => 'Material supplier',
            ],
            [
                'vendor_code' => 'V-006',
                'name' => 'Davao Electrical Solutions',
                'contact_person' => 'Michael Villanueva',
                'contact_no' => '09214567823',
                'email' => 'davaoelectrical@gmail.com',
                'address' => 'Quimpo Boulevard, Ecoland, Davao City, Davao del Sur',
                'vendor_type' => 'Service provider',
            ],
            [
                'vendor_code' => 'V-007',
                'name' => 'Mindanao Plumbing Services',
                'contact_person' => 'Jose Manuel Aquino',
                'contact_no' => '09176382945',
                'email' => 'mindanaoplumbing@gmail.com',
                'address' => 'Buhangin Road, Buhangin, Davao City, Davao del Sur',
                'vendor_type' => 'Service provider',
            ],
            [
                'vendor_code' => 'V-008',
                'name' => 'Davao Architectural and Design Services',
                'contact_person' => 'Andrea Marie Flores',
                'contact_no' => '09283156742',
                'email' => 'davaoads@gmail.com',
                'address' => 'Lanang, Davao City, Davao del Sur',
                'vendor_type' => 'Service provider',
            ],
            [
                'vendor_code' => 'V-009',
                'name' => 'Southern Surveying Solutions',
                'contact_person' => 'Patrick Lim',
                'contact_no' => '09165827431',
                'email' => 'southernsurveying@gmail.com',
                'address' => 'Roxas Avenue, Poblacion District, Davao City, Davao del Sur',
                'vendor_type' => 'Service provider',
            ],
            [
                'vendor_code' => 'V-010',
                'name' => 'Davao Heavy Equipment Rental',
                'contact_person' => 'Anthony Bautista',
                'contact_no' => '09227483156',
                'email' => 'davaoheavyequipment@gmail.com',
                'address' => 'Panacan, Davao City, Davao del Sur',
                'vendor_type' => 'Equipment rental',
            ],
            [
                'vendor_code' => 'V-011',
                'name' => 'Mindanao Crane and Machinery Rental',
                'contact_person' => 'Roberto Navarro',
                'contact_no' => '09178354621',
                'email' => 'mindanaocrane@gmail.com',
                'address' => 'Sasa, Davao City, Davao del Sur',
                'vendor_type' => 'Equipment rental',
            ],
            [
                'vendor_code' => 'V-012',
                'name' => 'Southern Backhoe and Excavator Rental',
                'contact_person' => 'Daniel Mercado',
                'contact_no' => '09264175839',
                'email' => 'southernmachinery@gmail.com',
                'address' => 'Mintal, Tugbok District, Davao City, Davao del Sur',
                'vendor_type' => 'Equipment rental',
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}
