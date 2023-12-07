<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\OperationTypesEnum;
use App\Models\RatingCategory;
use App\Models\Rent;
use App\Models\User;
use App\Models\Car;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operation>
 */
class OperationFactory extends Factory
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
            'sum' => Rent::get()->random()->duration * (RatingCategory::where('id', 
                (Car::where('id', 
                    Rent::get()->random()->car_id)
                ->get())[0]->category_id)
            ->get())[0]->rate,
            'type' => OperationTypesEnum::randomValue(),

            'rent_id' => Rent::get()->random()->id,
            'user_id' => User::get()->random()->id,
        ];
    }
}
