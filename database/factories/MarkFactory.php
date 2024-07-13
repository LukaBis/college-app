<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mark>
 */
class MarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mark' => collect(['A', 'B', 'C', 'D', 'E', 'F'])->random(),
            'description' => $this->faker->text(),
            'points' => $this->faker->randomDigit(),
        ];
    }
}
