<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PracticeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('practice_statuses')->insert([
            [
                'status' => 'Neschválená',
            ],
            [
                'status' => 'Schválená',
            ],
            [
                'status' => 'Ukončená úspešne',
            ],
            [
                'status' => 'Ukončená neúspešne',
            ],
        ]);
    }
}
