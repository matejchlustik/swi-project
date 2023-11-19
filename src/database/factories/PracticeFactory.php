<?php

namespace Database\Factories;

use App\Models\Practice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Practice>
 */
class PracticeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Practice::class;
    public function definition(): array
    {
        return [
            'from' => $this->faker->dateTimeBetween("2023/01/01","2023/01/31"),
            'to' => $this->faker->dateTimeBetween("2023/05/01","2023/05/31"),
            'user_id' => 5,
            'company_employee_id' => 1,
            'department_employee_id' => null,
            'program_id' => 2,
            'contract' => 'cesta ku suboru',
        ];
    }
}
