<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('majors')->insert([
            [
                'name' => 'informatika',
                'department_id'=> 1
            ],
            [
                'name' => 'fyzika',
                'department_id'=> 3
            ],
            [
                'name' => 'psychológia',
                'department_id'=> 12
            ],
            [
                'name' => 'histórie',
                'department_id'=> 18
            ],
                       
           
        ]);
    }
}
