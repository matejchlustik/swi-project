<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('programs')->insert([
            [
                'name' => 'aplikovaná informatika bakalársky',
                'short'=> 'AI22b',
                'major_id'=> 1
            ],
            [
                'name' => 'aplikovaná informatika magisterský',
                'short'=> 'AI22m',
                'major_id'=> 1
            ],
            [
                'name' => 'aplikovaná informatika doktarantský',
                'short'=> 'AI22d',
                'major_id'=> 1
            ],
            [
                'name' => 'fyzika materiálov bakalársky',
                'short'=> 'FM22b',
                'major_id'=> 2
            ],
            [
                'name' => 'fyzika materiálov magisterský',
                'short'=> 'FM22m',
                'major_id'=> 2
            ],
            [
                'name' => 'fyzika materiálov doktorantský',
                'short'=> 'FM22d',
                'major_id'=> 2
            ],
            [
                'name' => 'psychológia bakalársky',
                'short'=> 'PSY22b',
                'major_id'=> 3
            ],
            [
                'name' => 'psychológia magisterský',
                'short'=> 'PSY22m',
                'major_id'=> 3
            ],
            [
                'name' => 'psychológia doktorantský',
                'short'=> 'PSY22d',
                'major_id'=> 3
            ],
            [
                'name' => 'história bakalársky',
                'short'=> 'HI22b',
                'major_id'=> 4
            ],
            [
                'name' => 'história magisterský',
                'short'=> 'HI22bm',
                'major_id'=> 4
            ],
            [
                'name' => 'história doktorantský',
                'short'=> 'HI22d',
                'major_id'=> 4
            ],
                       
           
        ]);
    }
}
