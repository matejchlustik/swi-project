<?php

namespace Database\Factories;

use App\Models\CompanyDepartment;
use App\Models\PracticeOffer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PracticeOffer>
 */
class PracticeOffersFactory extends Factory
{
    protected $model = PracticeOffer::class;

    public function definition()
    {
        return [
            'description' => $this->faker->paragraph,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'company_department_id' => function () {
                return rand(1, 30);
            //return CompanyDepartment::factory()->create()->id;
            },
        ];
    }
}
