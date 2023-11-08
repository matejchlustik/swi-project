<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Company::class;
    public function definition(): array
    {
        return [
            'ICO'=>$this->faker->randomNumber(),
            'name' => $this->faker->company(),
            'city' => $this->faker->city(),
            'zip_code' => "95501",
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'street' => $this->faker->streetName(),
            'house_number' => $this->faker->buildingNumber(),

        ];
    }
}
