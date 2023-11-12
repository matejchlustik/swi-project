<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Company;
use App\Models\Program;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\DepartmentSeeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Company::factory(30)->create();

        $this->call([
            RoleSeeder::class,
            FacultySeeder::class,
            DepartmentSeeder::class,
            MajorSeeder::class,
            ProgramSeeder::class
        ]);

    }
}
