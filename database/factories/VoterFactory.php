<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voter>
 */
class VoterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "lastName" => fake()->lastName(),
            "document" => strval(fake()->numberBetween(10000000, 99999999)),
            "dob" => fake()->dateTimeBetween('-70 years', '-40 years')->format('Y-m-d'),
            "is_candidate" => fake()->boolean()
        ];
    }
}
