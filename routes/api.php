<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers\\'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::group(['namespace' => 'App\Http\Controllers\\', 'middleware' => 'jwt.auth'], function() {
	Route::get('/dictionary/cars', 'CarController@allCars');
	Route::get('/dictionary/car', 'CarController@certainCar');

	Route::get('/dictionary/user', 'UserContorller@index');
	Route::get('/dictionary/user/rent_history', 'UserContorller@showRentHistory');
	Route::get('/dictionary/user/operation_history', 'UserContorller@showOperationHistory');

	Route::post('/action/rent_open', 'RentContorller@rentOpen');
	Route::post('/action/rent_close', 'RentContorller@rentClose');
	Route::post('/action/adding_funds', 'UserContorller@addingFunds');

	Route::post('/dictionary/delete_user', 'UserContorller@destroy');
});

// "critical" requests with access only for "admins"
Route::group(['namespace' => 'App\Http\Controllers\\', 'middleware' => 'jwt.auth'], function() {
	Route::post('/dictionary/delete_car', 'CarController@destroy');
});