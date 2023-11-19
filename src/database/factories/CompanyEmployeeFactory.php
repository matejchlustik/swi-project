<?php

namespace Database\Factories;

use App\Models\CompanyEmployee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyEmployee>
 */
class CompanyEmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CompanyEmployee::class;
    public function definition(): array
    {
        return [
            'user_id' => 4,
            'company_id' => $this->faker->numberBetween(1,30),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
