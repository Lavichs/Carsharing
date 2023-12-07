<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\CarStatusesEnum;
use App\Enums\CarEnginesEnum;

use App\Models\RatingCategory;
use App\Models\Manufacturer;
use App\Models\ModelCar;
use App\Models\Brand;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $carNumber = random_int(0, 9) . $this->faker->randomLetter . $this->faker->randomLetter .
        $this->faker->randomLetter . random_int(0, 9) . random_int(0, 9);
        $CarRegion = random_int(1, 150);
        if ($CarRegion < 10) {
            $CarRegion = 0 . $CarRegion;
        };

        return [
            'id' => $this->faker->uuid,
            'status' => CarStatusesEnum::randomValue(),
            'engineType' => CarEnginesEnum::randomValue(),
            'number' => $carNumber,
            'region' => $CarRegion,
            'accidents' => random_int(0, 5) == 1 ? random_int(1, 5) : 0,
            'date_of_create' => $this->faker->date,

            'manufacturer_id' => Manufacturer::get()->random()->id,
            'model_id' => ModelCar::get()->random()->id,
            'brand_id' => Brand::get()->random()->id,
            'category_id' => RatingCategory::get()->random()->id,
            //
        ];
    }
}
