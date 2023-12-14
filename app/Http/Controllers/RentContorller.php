<?php

namespace App\Http\Controllers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use PhpAmqpLib\Message\AMQPMessage;
use App\Enums\RentStatusesEnum;
use Illuminate\Http\Request;
use App\Models\Rent;

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

        if (!array_key_exists('car_id', $params)) {
            return 'The required "car_id" argument is missing';
        }

        $connection = new AMQPStreamConnection(
            Config::get('rabbitmq.host'),
            Config::get('rabbitmq.port'),
            Config::get('rabbitmq.user'),
            Config::get('rabbitmq.pass'),
        );
        $channel = $connection->channel();

        $channel->queue_declare('rent', false, true, false, false);

        $currentRents = Rent::where('user_id', $user->id)->where('status_id', RentStatusesEnum::Open)->get();
        if (count($currentRents) === 0) {
            if ($user->score < 0) {
                return [1, 'Insufficient funds'];
            }

            $data = json_encode([
                'type' => 'open',
                'user_id' => $user->id,
                'car_id' => $params['car_id']
            ]);

            $msg = new AMQPMessage($data);
            $channel->basic_publish($msg, '', 'rent');


            return [0, 'Successfuly open'];
        } else {
            return [1, 'Already opened'];
        }

        $channel->close();
        $connection->close();
    }

    public function rentClose(Request $request)
    {
        // $faker = \Faker\Factory::create();
        $user = Auth::user();
        $params = $request->all();
        if (!array_key_exists('car_id', $params)) {
            return 'The required "car_id" argument is missing';
        }
        if (!array_key_exists('incidents', $params)) {
            return 'The required "incidents" argument is missing';
        }

        $connection = new AMQPStreamConnection(
            Config::get('rabbitmq.host'),
            Config::get('rabbitmq.port'),
            Config::get('rabbitmq.user'),
            Config::get('rabbitmq.pass'),
        );
        $channel = $connection->channel();

        $channel->queue_declare('rent', false, true, false, false);


        // dd($user->id);


        $currentRents = Rent::where('user_id', $user->id)
        ->where('status_id', RentStatusesEnum::Open)
        ->where('car_id', $params["car_id"])
        ->get();

        if (count($currentRents) > 0) {

            $data = json_encode([
                'type' => 'close',
                'user_id' => $user->id,
                'user' => $user,
                // 'car_id' => $params['car_id'],
                'incidents' => $params['incidents'],
                'currentRent' => $currentRents[0]
            ]);

            $msg = new AMQPMessage($data);
            $channel->basic_publish($msg, '', 'rent');

            return [0, 'Successfuly close'];
        } else {
            return [1, "Haven't open lease"];
        }

        $channel->close();
        $connection->close();
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
