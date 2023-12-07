<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RatingCategory;
use App\Models\Manufacturer;
use App\Models\Operation;
use App\Models\ModelCar;
use App\Models\Brand;
use App\Models\Rent;
use App\Models\User;
use App\Models\Car;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $TotalSstart = Carbon::now();
        $start = Carbon::now();

        Manufacturer::factory(10)->create();    // 20
        dump('Manufacturer seeded................' . $start->diff(Carbon::now())->s . 'sec');
        $start = Carbon::now();
        Brand::factory(10)->create();           // 15
        dump('Brand seeded.......................' . $start->diff(Carbon::now())->s . 'sec');
        $start = Carbon::now();
        ModelCar::factory(10)->create();
        dump('ModelCar seeded....................' . $start->diff(Carbon::now())->s . 'sec');
        RatingCategory::factory(5)->create();
        dump('RatingCategory seeded..............' . $start->diff(Carbon::now())->s . 'sec');
        $start = Carbon::now();
        Car::factory(10)->create();             // 1000
        dump('Car seeded.........................' . $start->diff(Carbon::now())->s . 'sec');
        $start = Carbon::now();
        User::factory(10)->create();
        dump('User seeded........................' . $start->diff(Carbon::now())->s . 'sec');
        $start = Carbon::now();
        Rent::factory(10)->create();            // 2000
        dump('Rent seeded........................' . $start->diff(Carbon::now())->s . 'sec');
        $start = Carbon::now();
        Operation::factory(10)->create();       // 1000
        dump('Operation seeded...................' . $start->diff(Carbon::now())->s . 'sec');
        $start = Carbon::now();

        dump('Total..............................' . $TotalSstart->diff(Carbon::now())->s . 'sec');




        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
