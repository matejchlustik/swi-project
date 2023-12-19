<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Feedback;
use App\Models\Practice;
use App\Models\PracticeRecord;
use App\Models\CompanyDepartment;
use App\Models\PracticeOffer;
use App\Models\CompanyEmployee;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\ProgramSeeder;
use Database\Seeders\DepartmentSeeder;
use Illuminate\Support\Facades\DB;


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
            PracticeStatusSeeder::class,
            FacultySeeder::class,
            DepartmentSeeder::class,
            MajorSeeder::class,
            ProgramSeeder::class,
            UserSeeder::class,
        ]);
        CompanyEmployee::factory(0)->create();
        Practice::factory(10)->create();
        PracticeRecord::factory(10)->create();
        CompanyDepartment::factory(30)->create();
        PracticeOffer::factory(10)->create();
        Comment::factory(10)->create();
        Feedback::factory(10)->create();
    }
}
