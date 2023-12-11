<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Enums\OperationTypesEnum;
use App\Enums\RentStatusesEnum;
use App\Models\RatingCategory;
use Illuminate\Http\Request;
use App\Models\Operation;
use App\Models\User;
use App\Models\Rent;
use App\Models\Car;

class RentContorller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function rentOpen(Request $request)
    {
        $user = Auth::user();
        $params = $request->all();
        $car = (Car::where('id', $params['car'])->get())[0];
        $faker = \Faker\Factory::create();

        $currentRents = Rent::where('user_id', $user->id)->where('status_id', RentStatusesEnum::Open)->get();
        if (count($currentRents) === 0) {
            if ($user->score < 0) {
                return [1, 'Insufficient funds'];
            }

            $rent = new Rent;
            $rent->id = $faker->uuid;
            $rent->duration = 0;
            $rent->date_of_rent = now();
            $rent->status_id = RentStatusesEnum::Open;
            $rent->car_id = $car->id;
            $rent->user_id = $user->id;

            $rent->save();
            return [0, 'Successfuly open'];
        } else {
            return [1, 'Already opened'];
        }
    }

    public function rentClose(Request $request)
    {
        $faker = \Faker\Factory::create();
        $user = Auth::user();
        // dd($user->id);
        $params = $request->all();
        if (!array_key_exists('car_id', $params)) {
            return 'The required "car_id" argument is missing';
        }
        if (!array_key_exists('incidents', $params)) {
            return 'The required "incidents" argument is missing';
        }

        $currentRents = Rent::where('user_id', $user->id)
        ->where('status_id', RentStatusesEnum::Open)
        ->where('car_id', $params["car_id"])
        ->get();

        if (count($currentRents) > 0) {

            $totalSum = 0;
            for ($i = 0; $i < count($currentRents); $i++) {
                $currentRents[$i]->status_id = $params['incidents'] == 0 ? RentStatusesEnum::Close : RentStatusesEnum::ClosedWithIncident;      // the status with which the rent will be closed
                $currentRents[$i]->save();      // closing the rent

                $time = now()->diff($currentRents[$i]->date_of_rent);
                $currentRents[$i]->duration_minutes = $time->i + $time->h * 60;     // time elapsed since the beginning of the lease
                $rent_time = $currentRents[$i]->duration_minutes;

                $current_car = (Car::where('id', $currentRents[$i]->car_id)->get())[0];     // the car for which the rental is open
                $rate_of_carrent_car = (RatingCategory::where('id', $current_car->category_id)->get())[0]->rate;    // the rate of the current car
                $sum = (int)round($rate_of_carrent_car * $rent_time);          // the amount of the current lease
                $totalSum += (int)round($rate_of_carrent_car * $rent_time); 

                if (0 < $user->rating && $user->rating < 5) {
                    $user->rating += $params['incidents'] == 0 ? 0.15 : -0.15 * $params['incidents'];
                };
                $current_car->accidents += (int)$params['incidents'];
                $current_car->save();

                $operation = new Operation;
                $operation->id = $faker->uuid;
                $operation->sum = $sum;
                $operation->type = OperationTypesEnum::Rent;
                $operation->rent_id = $currentRents[$i]->id;
                $operation->user_id = $user->id;
                $operation->save();
            };
            $user->score -= $totalSum;
            $user->save();

            return [0, 'Successfuly close', $totalSum];
        } else {
            return [1, "Haven't open lease"];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rent $rent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rent $rent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rent $rent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rent $rent)
    {
        //
    }
}
