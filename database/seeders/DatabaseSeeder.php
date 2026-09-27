<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ClientSeeder;
use Database\Seeders\MaterialSeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\PurchaseOrderSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VendorSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Richard Sombrio',
            'email' => 'fg_admin27@gmail.com',
            'password' => Hash::make('Admin@FortesGrounds27'),
            'role' => 'Admin'
        ]);

        $this->call([
            UserSeeder::class,
            EmployeeSeeder::class,
            ClientSeeder::class,
            ProjectSeeder::class,
            VendorSeeder::class,
            MaterialSeeder::class,
            ContractSeeder::class,
            ScheduleTaskSeeder::class,
            PurchaseOrderSeeder::class
        ]);
    }
}
