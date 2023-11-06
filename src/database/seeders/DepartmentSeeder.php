<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                'name' => 'Katedra informatiky',
                'short'=> 'KI',
                'faculty_id'=> 1

            ],
            [
                'name' => 'Katedra cestovného ruchu',
                'short'=> 'KCR',
                'faculty_id'=> 3

            ],
            [
                'name' => 'Katedra fyziky',
                'short'=> 'KF',
                'faculty_id'=> 1

            ],
            [
                'name' => 'Katedra botaniky a genetiky',
                'short'=> 'KBG',
                'faculty_id'=> 1

            ],
            [
                'name' => 'Katedra ekológie a environmentalistiky',
                'short'=> 'KEE',
                'faculty_id'=> 1

            ],
            [
                'name' => 'Katedra geografie, geoinformatiky a regionálneho rozvoja',
                'short'=> 'KGRR',
                'faculty_id'=> 1

            ],
            [
                'name' => 'Katedra chémie',
                'short'=> 'KCH',
                'faculty_id'=> 1

            ],
            [
                'name' => 'Katedra matematiky',
                'short'=> 'KM',
                'faculty_id'=> 1

            ],
            [
                'name' => 'Katedra zoológie a antropológie',
                'short'=> 'KZA',
                'faculty_id'=> 1

            ],
            [
                'name' => 'Katedra klinických disciplín a urgentnej medicíny',
                'short'=> 'KKDUM',
                'faculty_id'=> 2

            ],
            [
                'name' => 'Katedra ošetrovateľstva',
                'short'=> 'KO',
                'faculty_id'=> 2

            ],
            [
                'name' => 'Katedra psychologických vied',
                'short'=> 'KPSV',
                'faculty_id'=> 2

            ],
            [
                'name' => 'Katedra sociálnej práce a sociálnych vied',
                'short'=> 'KSPSV',
                'faculty_id'=> 2

            ],
            [
                'name' => 'Katedra anglistiky a amerikanistiky',
                'short'=> 'KAA',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra archeológie',
                'short'=> 'KARCH',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra etiky a estetiky',
                'short'=> 'KVAEE',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra filozofie a politológie',
                'short'=> 'KFI',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra histórie',
                'short'=> 'KHIS',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra masmediálnej komunikácie a reklamy',
                'short'=> 'KMKR',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra romanistiky a germanistiky',
                'short'=> 'KGER',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra slovanských filológií',
                'short'=> 'KSJL',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra translatológie',
                'short'=> 'KTR',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra žurnalistiky a nových médií',
                'short'=> 'KZUR',
                'faculty_id'=> 4

            ],
            [
                'name' => 'Katedra anglického jazyka a kultúry',
                'short'=> 'KAJK',
                'faculty_id'=> 5

            ],
            [
                'name' => 'Katedra hudby',
                'short'=> 'KH',
                'faculty_id'=> 5

            ],
            [
                'name' => 'Katedra pedagogiky',
                'short'=> 'KPG',
                'faculty_id'=> 5

            ],
            [
                'name' => 'Katedra pedagogickej a školskej psychológie',
                'short'=> 'KPSP',
                'faculty_id'=> 5

            ],
            [
                'name' => 'Katedra techniky a informačných technológii',
                'short'=> 'KTIT',
                'faculty_id'=> 5

            ],
            [
                'name' => 'Katedra telesnej výchovy a športu',
                'short'=> 'KTVS',
                'faculty_id'=> 5

            ],
            [
                'name' => 'Katedra výtvarnej tvorby a výchovy',
                'short'=> 'KVTV',
                'faculty_id'=> 5

            ],
    
        ]);
    }
}
