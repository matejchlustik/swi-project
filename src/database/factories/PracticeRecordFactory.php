<?php

namespace Database\Factories;

use App\Models\PracticeRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PracticeRecord>
 */
class PracticeRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = PracticeRecord::class;
    public function definition(): array
    {
        return [
            'from' => $this->faker->dateTimeBetween("2023/01/01","2023/01/31"),
            'to' => $this->faker->dateTimeBetween("2023/05/01","2023/05/31"),
            'description' => 'popis vykonanej cinnosti',
            'hours' => $this->faker->numberBetween(20,30),
            'practice_id' => 1,
        ];
    }
}
