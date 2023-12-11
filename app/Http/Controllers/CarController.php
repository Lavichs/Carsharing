<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rent;
use App\Models\Brand;
use App\Models\ModelCar;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Enums\CarEnginesEnum;
use App\Enums\CarStatusesEnum;
use App\Models\RatingCategory;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allCars()
    {
        $cars = Car::all();
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
        $car = (Car::where('id', $params['id'])->get())[0];

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
    public function create()
    {
        //
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
        $params = $request->all();
        $car = (Car::where('id', $params['id'])->get())[0];
        $car->delete();

        return response()->json([
            'message' => 'Successfuly deleted'
        ]);
    }
}