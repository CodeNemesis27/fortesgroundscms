<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Juan Carlos Dela Cruz',
                'email' => 'juan.delacruz@gmail.com',
                'contact_no' => '09172458136',
                'address' => 'J.P. Laurel Avenue, Bajada, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Roberto Miguel Garcia',
                'email' => 'roberto.garcia@gmail.com',
                'contact_no' => '09186342751',
                'address' => 'Buhangin Road, Buhangin, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Patricia Anne Reyes',
                'email' => 'patricia.reyes@gmail.com',
                'contact_no' => '09278413652',
                'address' => 'Lanang, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Mark Anthony Mendoza',
                'email' => 'mark.mendoza@gmail.com',
                'contact_no' => '09197254836',
                'address' => 'Quimpo Boulevard, Ecoland, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Christine Marie Flores',
                'email' => 'christine.flores@gmail.com',
                'contact_no' => '09214567823',
                'address' => 'Roxas Avenue, Poblacion District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Ramon Eduardo Navarro',
                'email' => 'ramon.navarro@gmail.com',
                'contact_no' => '09176382945',
                'address' => 'Toril District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Jennifer Mae Aquino',
                'email' => 'jennifer.aquino@gmail.com',
                'contact_no' => '09283156742',
                'address' => 'Catalunan Grande, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Daniel Joseph Bautista',
                'email' => 'daniel.bautista@gmail.com',
                'contact_no' => '09165827431',
                'address' => 'Panacan, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Andrea Marie Villanueva',
                'email' => 'andrea.villanueva@gmail.com',
                'contact_no' => '09227483156',
                'address' => 'Mintal, Tugbok District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Michael Joseph Lim',
                'email' => 'michael.lim@gmail.com',
                'contact_no' => '09178354621',
                'address' => 'Sasa, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Sophia Grace Mercado',
                'email' => 'sophia.mercado@gmail.com',
                'contact_no' => '09264175839',
                'address' => 'Ma-a, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Carlos Emmanuel Torres',
                'email' => 'carlos.torres@gmail.com',
                'contact_no' => '09185723649',
                'address' => 'Bacaca, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Beatrice Louise Castillo',
                'email' => 'beatrice.castillo@gmail.com',
                'contact_no' => '09206842197',
                'address' => 'Obrero, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Francisco Manuel Ramos',
                'email' => 'francisco.ramos@gmail.com',
                'contact_no' => '09193487256',
                'address' => 'Agdao, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Nicole Andrea Fernandez',
                'email' => 'nicole.fernandez@gmail.com',
                'contact_no' => '09275364812',
                'address' => 'Baliok, Talomo District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Jose Miguel Santiago',
                'email' => 'jose.santiago@gmail.com',
                'contact_no' => '09174638295',
                'address' => 'Calinan District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Isabella Marie Dominguez',
                'email' => 'isabella.dominguez@gmail.com',
                'contact_no' => '09217356482',
                'address' => 'Puan, Talomo District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Gabriel Antonio Perez',
                'email' => 'gabriel.perez@gmail.com',
                'contact_no' => '09168245371',
                'address' => 'Bunawan District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Rachel Mae Manalo',
                'email' => 'rachel.manalo@gmail.com',
                'contact_no' => '09286473158',
                'address' => 'Bago Aplaya, Talomo District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Zaira Mae Lim',
                'email' => 'zaira.lim@gmail.com',
                'contact_no' => '09389002145',
                'address' => 'Chrysolite, Matina Crossing, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Leon Scott Kennedy',
                'email' => 'leon.kennedy@gmail.com',
                'contact_no' => '09321402145',
                'address' => 'Deca Homes Phase 10, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Kristine Mae Cayanong',
                'email' => 'kristine.cayanong@gmail.com',
                'contact_no' => '09201209432',
                'address' => 'Brgy. Matina Baio, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Ellyn Jane Guiang',
                'email' => 'ellyn.guiang@gmail.com',
                'contact_no' => '09678032345',
                'address' => 'Wellspring Village, Catalunan Pequeno, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Jesthril Mae Estrada',
                'email' => 'jesthril.estrada@gmail.com',
                'contact_no' => '09671122345',
                'address' => 'Molave St., Brgy. Relocation, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Matthew John Salazar',
                'email' => 'matthew.salazar@gmail.com',
                'contact_no' => '09191234567',
                'address' => 'Bangkal, Talomo District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Angela Mae Cabrera',
                'email' => 'angela.cabrera@gmail.com',
                'contact_no' => '09271234568',
                'address' => 'Bago Gallera, Talomo District, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Nathaniel James Rivera',
                'email' => 'nathaniel.rivera@gmail.com',
                'contact_no' => '09182345679',
                'address' => 'J.P. Laurel Avenue, Lanang, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Camille Rose Santos',
                'email' => 'camille.santos@gmail.com',
                'contact_no' => '09391234560',
                'address' => 'Bajada, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Jerome Patrick Villafuerte',
                'email' => 'jerome.villafuerte@gmail.com',
                'contact_no' => '09281234561',
                'address' => 'Matina Aplaya, Davao City, Davao del Sur',
            ],
            [
                'name' => 'Mia Alexandra Gonzales',
                'email' => 'mia.gonzales@gmail.com',
                'contact_no' => '09691234562',
                'address' => 'Buhangin, Davao City, Davao del Sur',
            ],
        ];

        // Assign each client a random date within April 1 – August 31, 2026,
        // then sort chronologically before inserting. Random offsets mean
        // each month naturally ends up with a different number of clients,
        // rather than an even, predictable spread.
        $rangeStart = Carbon::parse('2026-04-01');
        $rangeEnd = Carbon::parse('2026-08-31');
        $totalDays = $rangeStart->diffInDays($rangeEnd);

        $scheduled = collect($clients)
            ->map(fn(array $client) => [
                'client' => $client,
                'created_at' => $rangeStart->copy()
                    ->addDays(mt_rand(0, $totalDays))
                    ->setTime(mt_rand(8, 18), mt_rand(0, 59)),
            ])
            ->sortBy('created_at')
            ->values();

        foreach ($scheduled as $entry) {
            $client = $entry['client'];
            $createdAt = $entry['created_at'];

            $user = User::create([
                'name' => $client['name'],
                'email' => $client['email'],
                'password' => Hash::make('Client@FortesGrounds27'),
                'role' => 'Client',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            Client::create([
                'user_id' => $user->id,
                'contact_no' => $client['contact_no'],
                'address' => $client['address'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
