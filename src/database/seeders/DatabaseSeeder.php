<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Company;
use App\Models\CompanyEmployee;
use App\Models\Practice;
use App\Models\PracticeRecord;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\ProgramSeeder;
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
            ProgramSeeder::class,
            UserSeeder::class,
        ]);

        CompanyEmployee::factory(1)->create();
        Practice::factory(1)->create();
        PracticeRecord::factory(10)->create();
    }
}
