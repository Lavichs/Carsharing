<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RatingCategory>
 */
class RatingCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = random_int(0, 50) * 10;
        return [
            'rating' => $rating > 250 ? $rating : 0,
            'rate' => random_int(4, 10),
        ];
    }
}
