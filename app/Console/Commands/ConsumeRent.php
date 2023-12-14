<?php

namespace App\Console\Commands;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Support\Facades\Config;
use App\Enums\OperationTypesEnum;
use Illuminate\Console\Command;
use App\Enums\RentStatusesEnum;
use App\Models\RatingCategory;
use App\Models\Operation;
use App\Models\User;
use App\Models\Rent;
use App\Models\Car;

class ConsumeRent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consume-rent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $connection = new AMQPStreamConnection(
            Config::get('rabbitmq.host'),
            Config::get('rabbitmq.port'),
            Config::get('rabbitmq.user'),
            Config::get('rabbitmq.pass'),
        );
        $channel = $connection->channel();

        $channel->queue_declare('rent', false, true, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            $data = json_decode($msg->body);
            $faker = \Faker\Factory::create();

            // At this point, the assumption is made that the data comes in the expected form. 
            // I have not been able to verify the existence of a specific attribute 
            // with built-in functions. it remains only to believe)

            if ($data->type == 'open') {
                // print_r($data);

                $car = (Car::where('id', $data->car_id)->get())[0];

                $rent = new Rent;
                $rent->id = $faker->uuid;
                $rent->duration = 0;
                $rent->date_of_rent = now();
                $rent->status_id = RentStatusesEnum::Open;
                $rent->car_id = $car->id;
                $rent->user_id = $data->user_id;

                $rent->save();

                print_r('rent open' . "\n");

                // print_r($rent);
            }

            if ($data->type == 'close') {
                $currentRent = $data->currentRent;
                $user = $data->user;

                $user = (User::where('id', $data->user->id)->get())[0];
                $currentRent = (Rent::where('user_id', $user->id)->where('status_id', RentStatusesEnum::Open)->get())[0];

                $currentRent->status_id = '0'; // $data->incidents == 0 ? RentStatusesEnum::Close->value : RentStatusesEnum::ClosedWithIncident->value;      // the status with which the rent will be closed
                $currentRent->save();      // closing the rent

                $time = now()->diff($currentRent->date_of_rent);
                $currentRent->duration_minutes = $time->i + $time->h * 60;     // time elapsed since the beginning of the lease
                $rent_time = $currentRent->duration_minutes;

                $current_car = (Car::where('id', $currentRent->car_id)->get())[0];     // the car for which the rental is open
                $rate_of_carrent_car = (RatingCategory::where('id', $current_car->category_id)->get())[0]->rate;    // the rate of the current car
                $sum = (int)round($rate_of_carrent_car * $rent_time);          // the amount of the current lease

                if (0 < $user->rating && $user->rating < 5) {
                    $user->rating += $data->incidents == 0 ? 0.15 : -0.15 * $data->incidents;
                };
                $current_car->accidents += (int)$data->incidents;
                $current_car->save();

                $operation = new Operation;
                $operation->id = $faker->uuid;
                $operation->sum = $sum;
                $operation->type = OperationTypesEnum::Rent;
                $operation->rent_id = $currentRent->id;
                $operation->user_id = $user->id;
                $operation->save();

                $user->score -= $sum;
                $user->save();

                print_r('rent close. Sum:  ' . $sum . "\n");
            }

        };

        $channel->basic_consume('rent', '', false, true, false, false, $callback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }
}
