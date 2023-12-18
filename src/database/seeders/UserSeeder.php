<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('users')->insert([
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'testadmin@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 1,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'testdephead@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 2,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'testdepemployee@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 3,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'testcompanyrepresentative@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 4,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'teststudent@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 5,
                'email_verified_at' => now(),
            ],
        ]);
        DB::table('department_employees')->insert([
            [
                'user_id' => 2,
                'department_id' => 1,
                'from'=>now()
            ],
            [
                'user_id' => 3,
                'department_id' => 1,
                'from'=>now()
            ]
        ]);
        DB::table('company_employees')->insert([
            [
                'user_id' => 4,
                'company_id' => 1,
                'phone' => "+421999111222"
            ],
        ]);
    }
}
