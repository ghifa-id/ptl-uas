<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'username' => 'administrator',
                'email' => 'administrator@mail.com',
                'phone_number' => '081100000000',
                'department_id' => null,
                'password' => bcrypt('administrator123'),
                'role' => 'administrator',
                'status' => 'active',
            ],
            [
                'name' => 'Manager',
                'username' => 'manager',
                'email' => 'manager@mail.com',
                'phone_number' => '081100000001',
                'department_id' => null,
                'password' => bcrypt('manager123'),
                'role' => 'manager',
                'status' => 'active',
            ],
            [
                'name' => 'Applicant',
                'username' => 'applicant',
                'email' => 'applicant@mail.com',
                'phone_number' => '081100000002',
                'department_id' => null,
                'password' => bcrypt('applicant123'),
                'role' => 'applicant',
                'status' => 'active',
            ],
        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
