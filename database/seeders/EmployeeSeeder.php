<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['first_name' => 'Juan', 'last_name' => 'Dela Cruz', 'position' => 'Project Engineer', 'employee_type' => 'Regular', 'date_hired' => '2019-03-11', 'basic_rate' => 35000.00, 'rate_type' => 'Monthly', 'contact_no' => '09171234501', 'address' => 'Purok 3, Catalunan Grande, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Ricardo', 'last_name' => 'Manalo', 'position' => 'Site Supervisor', 'employee_type' => 'Regular', 'date_hired' => '2020-06-15', 'basic_rate' => 28000.00, 'rate_type' => 'Monthly', 'contact_no' => '09182345612', 'address' => 'Purok 7, Matina, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Eduardo', 'last_name' => 'Torres', 'position' => 'Foreman', 'employee_type' => 'Regular', 'date_hired' => '2018-01-22', 'basic_rate' => 850.00, 'rate_type' => 'Daily', 'contact_no' => '09193456723', 'address' => 'J.P. Laurel Avenue, Bajada, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Rodel', 'last_name' => 'Aquino', 'position' => 'Mason', 'employee_type' => 'Project based', 'date_hired' => '2023-02-10', 'basic_rate' => 650.00, 'rate_type' => 'Daily', 'contact_no' => '09204567834', 'address' => 'Toril District, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Danilo', 'last_name' => 'Bautista', 'position' => 'Carpenter', 'employee_type' => 'Project based', 'date_hired' => '2022-11-05', 'basic_rate' => 620.00, 'rate_type' => 'Daily', 'contact_no' => '09215678945', 'address' => 'Buhangin, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Arnel', 'last_name' => 'Villanueva', 'position' => 'Electrician', 'employee_type' => 'Contractual', 'date_hired' => '2021-07-19', 'basic_rate' => 700.00, 'rate_type' => 'Daily', 'contact_no' => '09226789056', 'address' => 'Lanang, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Renato', 'last_name' => 'Gonzales', 'position' => 'Plumber', 'employee_type' => 'Contractual', 'date_hired' => '2021-09-30', 'basic_rate' => 680.00, 'rate_type' => 'Daily', 'contact_no' => '09237890167', 'address' => 'Sasa, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Bienvenido', 'last_name' => 'Aguilar', 'position' => 'Heavy Equipment Operator', 'employee_type' => 'Regular', 'date_hired' => '2019-10-14', 'basic_rate' => 900.00, 'rate_type' => 'Daily', 'contact_no' => '09248901278', 'address' => 'Agdao, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Noel', 'last_name' => 'Castillo', 'position' => 'Welder', 'employee_type' => 'Project based', 'date_hired' => '2023-05-08', 'basic_rate' => 640.00, 'rate_type' => 'Daily', 'contact_no' => '09259012389', 'address' => 'Talomo, Davao City, Davao del Sur', 'status' => 'On-leave'],
            ['first_name' => 'Bayani', 'last_name' => 'Fernandez', 'position' => 'Laborer', 'employee_type' => 'Project based', 'date_hired' => '2024-01-16', 'basic_rate' => 481.00, 'rate_type' => 'Daily', 'contact_no' => '09261123490', 'address' => 'Communal, Buhangin, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Mark', 'last_name' => 'Domingo', 'position' => 'Laborer', 'employee_type' => 'Project based', 'date_hired' => '2024-03-22', 'basic_rate' => 481.00, 'rate_type' => 'Daily', 'contact_no' => '09272234501', 'address' => 'Panacan, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Ronnie', 'last_name' => 'Salazar', 'position' => 'Driver', 'employee_type' => 'Regular', 'date_hired' => '2020-02-04', 'basic_rate' => 18000.00, 'rate_type' => 'Monthly', 'contact_no' => '09283345612', 'address' => 'Bunawan, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Ferdinand', 'last_name' => 'Navarro', 'position' => 'Warehouse Staff', 'employee_type' => 'Regular', 'date_hired' => '2021-04-27', 'basic_rate' => 16000.00, 'rate_type' => 'Monthly', 'contact_no' => '09294456723', 'address' => 'Catalunan Pequeño, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Antonio', 'last_name' => 'Pascual', 'position' => 'Quantity Surveyor', 'employee_type' => 'Regular', 'date_hired' => '2019-08-12', 'basic_rate' => 32000.00, 'rate_type' => 'Monthly', 'contact_no' => '09305567834', 'address' => 'Matina, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Maria', 'last_name' => 'Santos', 'position' => 'HR Officer', 'employee_type' => 'Regular', 'date_hired' => '2018-05-03', 'basic_rate' => 25000.00, 'rate_type' => 'Monthly', 'contact_no' => '09316678945', 'address' => 'J.P. Laurel Avenue, Bajada, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Ana', 'last_name' => 'Reyes', 'position' => 'Accountant', 'employee_type' => 'Regular', 'date_hired' => '2017-09-25', 'basic_rate' => 30000.00, 'rate_type' => 'Monthly', 'contact_no' => '09327789056', 'address' => 'Purok 3, Catalunan Grande, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Rosario', 'last_name' => 'Cruz', 'position' => 'Admin Staff', 'employee_type' => 'Regular', 'date_hired' => '2020-12-01', 'basic_rate' => 17000.00, 'rate_type' => 'Monthly', 'contact_no' => '09338890167', 'address' => 'Lanang, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Grace', 'last_name' => 'Mendoza', 'position' => 'Procurement Officer', 'employee_type' => 'Regular', 'date_hired' => '2019-06-18', 'basic_rate' => 24000.00, 'rate_type' => 'Monthly', 'contact_no' => '09349901278', 'address' => 'Sasa, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Josephine', 'last_name' => 'Ramos', 'position' => 'Draftsman', 'employee_type' => 'Contractual', 'date_hired' => '2022-04-09', 'basic_rate' => 20000.00, 'rate_type' => 'Monthly', 'contact_no' => '09351012389', 'address' => 'Toril District, Davao City, Davao del Sur', 'status' => 'Active'],
            ['first_name' => 'Liza', 'last_name' => 'Flores', 'position' => 'Safety Officer', 'employee_type' => 'Regular', 'date_hired' => '2021-01-27', 'basic_rate' => 22000.00, 'rate_type' => 'Monthly', 'contact_no' => '09362123490', 'address' => 'Buhangin, Davao City, Davao del Sur', 'status' => 'Terminated'],
        ];

        foreach ($employees as $index => $data) {
            $sequence = $index + 1;

            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => Str::slug($data['first_name'] . '.' . $data['last_name'], '.') . '@gmail.com',
                'password' => Hash::make('Emp@FortesGrounds27'),
                'role' => 'Employee',
            ]);

            Employee::create([
                'user_id' => $user->id,
                'employee_code' => 'EMP-' . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'position' => $data['position'],
                'employee_type' => $data['employee_type'],
                'date_hired' => $data['date_hired'],
                'basic_rate' => $data['basic_rate'],
                'rate_type' => $data['rate_type'],
                'contact_no' => $data['contact_no'],
                'address' => $data['address'],
                'status' => $data['status'],
            ]);
        }
    }
}
