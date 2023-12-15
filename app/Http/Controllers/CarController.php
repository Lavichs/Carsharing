<?php

namespace App\Http\Controllers;

use \Cache;
use App\Models\Car;
use App\Models\Rent;
use App\Models\Brand;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Enums\CarEnginesEnum;
use App\Enums\CarStatusesEnum;
use App\Models\RatingCategory;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allCars()
    {
        $cars = Cache::remember('cars', now()->addMinutes(5), function() {
            return Car::all();
        });

        // $cars = Car::all();
        $models = ModelCar::all();
        $brands = Brand::all();
        $manufacturers = Manufacturer::all();
        $result = [];

        for ($i = 0; $i < count($cars); $i++) {
            array_push($result, [
            'id' => $cars[$i]->id,
            'model' => $models[($cars[$i]->model_id) - 1]->model,
            'brand' => $brands[($cars[$i]->brand_id) - 1]->brand,
            'price (rub/minute)' => (RatingCategory::where('id', 
                (Car::where('id', 
                    Rent::get()->random()->car_id)
                ->get())[0]->category_id)
            ->get())[0]->rate,
            'status' => CarStatusesEnum::from($cars[$i]->status)->name,
            ]);
        };

        return $result;
    }

    /**
     * Display a certain resource.
     */
    public function certainCar(Request $request)
    {
        $params = $request->all();
        if (!array_key_exists('car_id', $params)) {
            return 'The required "car_id" argument is missing';
        }

        if (Cache::has($params['car_id'])) {
            $car = Cache::get($params['car_id']);
        }
        else {
            $car = (Car::where('id', $params['car_id'])->get())[0];
            Cache::put($params['car_id'], $car, now()->addMinutes(10));
        }

        // $car = Cache::remember($params['car_id'], now()->addMinutes(5), function($params) {
        //     return (Car::where('id', $params['car_id'])->get())[0];
        // }); 

        $models = ModelCar::all();
        $brands = Brand::all();
        $manufacturers = Manufacturer::all();
        return [
            'id' => $car->id,
            'status' => CarStatusesEnum::from($car->status)->name,
            'price (rub/minute)' => (RatingCategory::where('id', 
                (Car::where('id', 
                    Rent::get()->random()->car_id)
                ->get())[0]->category_id)
            ->get())[0]->rate,
            'model' => $models[($car->model_id) - 1]->model,
            'brand' => $brands[($car->brand_id) - 1]->brand,
            'engineType' => CarEnginesEnum::from($car->engineType)->name,
            'accidents' => $car->accidents,
            'number' => $car->number,
            'region' => $car->region,
            'manufacturer' => $manufacturers[($car->manufacturer_id) - 1]->title,
            'date_of_create' => $car->date_of_create,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->isAdmin) {
            $params = $request->all();
            if (!array_key_exists('status', $params)) {
                return 'The required "status" argument is missing';
            };
            if (!array_key_exists('engineType', $params)) {
                return 'The required "engineType" argument is missing';
            };
            if (!array_key_exists('number', $params)) {
                return 'The required "number" argument is missing';
            };
            if (!array_key_exists('region', $params)) {
                return 'The required "region" argument is missing';
            };
            if (!array_key_exists('accidents', $params)) {
                return 'The required "accidents" argument is missing';
            };
            if (!array_key_exists('date_of_create', $params)) {
                return 'The required "date_of_create" argument is missing';
            };
            if (!array_key_exists('manufacturer_id', $params)) {
                return 'The required "manufacturer_id" argument is missing';
            };
            if (!array_key_exists('model_id', $params)) {
                return 'The required "model_id" argument is missing';
            };
            if (!array_key_exists('brand_id', $params)) {
                return 'The required "brand_id" argument is missing';
            };
            if (!array_key_exists('category_id', $params)) {
                return 'The required "category_id" argument is missing';
            };

            $faker = \Faker\Factory::create();

            $car = Car::where('number', $params['number'])->get();

            if (count($car) > 0) {
                return 'Car with this number already exist';
            }
            
            try {
                $car = new Car;
                $car->id = $faker->uuid;
                $car->status = $params['status'];
                $car->engineType = $params['engineType'];
                $car->number = $params['number'];
                $car->region = $params['region'];
                $car->accidents = $params['accidents'];
                $car->date_of_create = date('Y-m-d', strtotime($params['date_of_create']));

                // $manufacturer = (Manufacturer::where('id', $params['manufacturer_id'])->get())[0];
                // dd($manufacturer);
                $car->manufacturer_id = $params['manufacturer_id'];
                $car->model_id = $params['model_id'];
                $car->brand_id = $params['brand_id'];
                $car->category_id = $params['category_id'];

                $car->save();

                return $car;
            } catch (Exception $e) {
                return 'Creation error' . $e->getMessage() . "\n";
            }
        }
        return response()->json([
            'message' => 'insufficient user rights'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($user->isAdmin) {
            $params = $request->all();
            $car = (Car::where('id', $params['id'])->get())[0];
            $car->delete();

            return response()->json([
                'message' => 'Successfuly deleted'
            ]);
        }
        return response()->json([
            'message' => 'insufficient user rights'
        ]);        
    }
}
