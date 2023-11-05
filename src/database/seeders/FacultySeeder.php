<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faculties')->insert([
            [
                'name' => 'Fakulta prírodných vied a informatiky',
                'short'=> 'FPVaI'
            ],
            [
                'name' => 'Fakulta sociálnych vied a zdravotníctva',
                'short'=> 'FSVaZ'
            ],
            [
                'name' => 'Fakulta stredoeurópskych štúdií',
                'short'=> 'FSŠ'
            ],
            [
                'name' => 'Filozofická fakulta',
                'short'=> 'FF'
            ],
            [
                'name' => 'Pedagogická fakulta',
                'short'=> 'PF'
            ],
           
        ]);
    }
}
