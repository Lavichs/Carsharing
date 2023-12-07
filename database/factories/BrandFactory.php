<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = [
        'Volvo', 'Chevrolet', 'Renault', 'Bentley', 'Porsche', 'Nissan', 'Acura', 'Mercedes-Benz',
        'Toyota', 'Jaguar', 'Geely', 'Land Rover', 'Suzuki', 'Alfa Romeo', 'Porsche', 'Mitsubishi',
        'Adler', 'Alfa Romeo', 'Cadillac', 'Daewoo', 'Lamborghini', 'Austin', 'BMW', 'Isuzu', 'Jaguar',
        'Mitsubishi', 'Audi', 'Alfa Romeo', 'Rolls-Royce', 'Lexus', 'Lamborghini', 'Bugatti', 'Toyota',
        'Peugeot', 'Volkswagen', 'Aero', 'Nissan', 'Volvo', 'Bentley', 'Ferrari', 'Mazda'
        ];
        $brands = array_unique($brands);

        return [
            'brand' => $brands[array_rand($brands)],
        ];
    }
}
