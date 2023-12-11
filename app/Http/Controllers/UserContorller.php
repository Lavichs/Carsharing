<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rent;
use App\Models\Operation;
use Illuminate\Http\Request;
use App\Enums\UserStatusesEnum;
use App\Enums\RentStatusesEnum;
use App\Enums\OperationTypesEnum;
use Illuminate\Support\Facades\Auth;

class UserContorller extends Controller
{
    /**
     * Display a user.
     */
    public function index()
    {
        $user = Auth::user();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => UserStatusesEnum::from($user->status)->name,
            'score' => $user->score,
            'rating' => $user->rating / 100,
            'telNumber' => $user->telNumber,
        ];
    }

    /**
     * Display a history of the rent.
     */
    public function showRentHistory()
    {
        $user = Auth::user();
        $rents = Rent::where('user_id', $user->id)->get();

        $result = [];
        for ($i = 0; $i < count($rents); $i++) {
            array_push($result, [
                'duration' => $rents[$i]->duration,
                'date_of_rent' => $rents[$i]->date_of_rent,
                'status' => RentStatusesEnum::from($rents[$i]->status)->name,
            ]);
        }

        return $result;
    }

    /**
     * Display a history of the operation.
     */
    public function showOperationHistory()
    {
        $user = Auth::user();
        $operation = Rent::where('user_id', $user->id)->get();

        $result = [];
        for ($i = 0; $i < count($operation); $i++) {
            array_push($result, [
                'sum' => $operation[$i]->sum,
                'type' => OperationTypesEnum::from($operation[$i]->type)->name,
            ]);
        }

        return $result;
    }

    public function addingFunds(Request $request)
    {
        $user = Auth::user();
        $params = $request->all();
        $faker = \Faker\Factory::create();

        if (!array_key_exists('fund', $params)) {
            return 'The required "fund" argument is missing';
        }
        if ($params['fund'] < 0) {
            return 'the "fund" argument must be greater than zero';
        }

        $operation = new Operation;
        $operation->id = $faker->uuid;
        $operation->sum = $params['fund'];
        $operation->type = OperationTypesEnum::Replenishment;
        $operation->user_id = $user->id;

        $user->score += $params['fund'];

        $operation->save();
        $user->save();

        return 'Successfuly adding. Current funds: ' . $user->score;
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
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $params = $request->all();
        $User = (User::where('id', $params['id'])->get())[0];
        $User->delete();

        return response()->json([
            'message' => 'Successfuly deleted'
        ]);
    }
}
