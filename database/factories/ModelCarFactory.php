<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModelCar>
 */
class ModelCarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $models = [
            'Kia Rio', 'Kia Rio X-Line', 'Renault Kaptur', 'Skoda Octavia', 'Audi A3', 'Audi Q3', 'Skoda Rapid', 'Porsche Macan', 'Porsche 911 Carrera', 'BMW 5 Series', 'Mercedes-Benz E-Class', 'Volkswagen Polo', 'Genesis G70', 'Citroen Jumpy'
        ];
        $models = array_unique($models);

        return [
            'model' => $models[array_rand($models)],
        ];
    }
}
