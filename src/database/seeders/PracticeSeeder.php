<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PracticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0;$i<5;$i++) {
            $start_date_start = "2023/01/01";
            $end_date_start = "2023/01/31";
            $from = mt_rand(strtotime($start_date_start), strtotime($end_date_start));

            $start_date_end = "2023/05/01";
            $end_date_end = "2023/05/31";
            $to = mt_rand(strtotime($start_date_end), strtotime($end_date_end));
$add;

            DB::table('practices')->insert([
                [
                    'from' => date('Y-m-d', $from),
                    'to' => date('Y-m-d', $to),
                    'user_id' => 5,
                    'company_employee_id' => 1,
                    'department_employee_id' => null,
                    'program_id' => 2,
                    'contract' => 'kontrakt',
                ]
            ]);
        }
    }
}
