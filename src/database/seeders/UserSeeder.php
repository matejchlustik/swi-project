<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'testadmin@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 1,
            ],
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'testdephead@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 2,
            ],
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'testdepemployee@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 3,
            ],
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'testcompanyrepresentative@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 4,
            ],
            [
                'first_name' => 'Zdeno',
                'last_name' => 'Poruba',
                'email' => 'teststudent@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => 5,
            ],
        ]);
    }
}
