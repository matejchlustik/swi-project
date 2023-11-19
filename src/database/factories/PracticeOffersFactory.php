<?php

namespace Database\Factories;

use App\Models\CompanyDepartment;
use App\Models\PracticeOffers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PracticeOffers>
 */
class PracticeOffersFactory extends Factory
{
    protected $model = PracticeOffers::class;

    public function definition()
    {
        return [
            'description' => $this->faker->paragraph,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'company_department_id' => function () {
                return CompanyDepartment::factory()->create()->id;
            },
        ];
    }
}
