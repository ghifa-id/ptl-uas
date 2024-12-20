<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uuidStarted = [
            1 => Uuid::uuid4()->toString(),
            2 => Uuid::uuid4()->toString(),
            3 => Uuid::uuid4()->toString(),
        ];

        $departments = [
            [
                'uuid' => $uuidStarted[1],
                'code' => 'AAA001',
                'name' => 'A',
            ],
            [
                'uuid' => $uuidStarted[2],
                'code' => 'BBB001',
                'name' => 'B',
            ],
            [
                'uuid' => $uuidStarted[3],
                'code' => 'CCC001',
                'name' => 'C',
            ],
        ];

        foreach ($departments as $key => $department) {
            Department::create($department);
        }

        $users = [
            [
                'name' => 'Administrator',
                'username' => 'administrator',
                'email' => 'administrator@mail.com',
                'phone_number' => '081100000000',
                'department_id' => $uuidStarted[1],
                'password' => bcrypt('administrator123'),
                'role' => 'bendahara',
                'status' => 'active',
            ],
            [
                'name' => 'Manager',
                'username' => 'manager',
                'email' => 'manager@mail.com',
                'phone_number' => '081100000001',
                'department_id' => $uuidStarted[1],
                'password' => bcrypt('manager123'),
                'role' => 'kasubag',
                'status' => 'active',
            ],
            [
                'name' => 'Applicant',
                'username' => 'applicant',
                'email' => 'applicant@mail.com',
                'phone_number' => '081100000002',
                'department_id' => $uuidStarted[1],
                'password' => bcrypt('applicant123'),
                'role' => 'staff',
                'status' => 'active',
            ],
        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
