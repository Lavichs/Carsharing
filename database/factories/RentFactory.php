<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\RentStatusesEnum;

use App\Models\User;
use App\Models\Car;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rent>
 */
class RentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'duration' => random_int(30, 300),
            'date_of_rent' => $this->faker->date,
            'status_id' => RentStatusesEnum::randomValue(),
            'car_id' => Car::get()->random()->id,
            'user_id' => User::get()->random()->id,
            //
        ];
    }
}
