<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyDepartment>
 */
class CompanyDepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'companies_id' => function () {
                return rand(1, 30);
            },
            'departments_id' => function () {
                return rand(1, 30);
            },
        ];
    }
}
