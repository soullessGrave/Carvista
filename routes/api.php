<?php

use App\Http\Controllers\carController;
use App\Http\Controllers\dealershipController;
use App\Http\Controllers\userController;
use App\Models\Car;
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

Route::post('userlogin', [userController::class,'login']);
Route::post('userRegister', [userController::class,'register']);

Route::post('dealershiplogin', [dealershipController::class,'login']);
Route::post('dealershipRegister', [dealershipController::class,'register']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
// Route::apiResource('carDeal', carController::class);

Route::get('usersCars', [carController::class,'index']);
Route::get('userCars', [carController::class,'showUserCars']);
Route::post('addCarDeal', [carController::class,'userCarStore']);
Route::put('updateCarDeal/{id}', [carController::class,'update']);
});

Route::group([

    'middleware' => 'auth:dealership',
    'prefix' => 'dealership'

], function ($router) {

    Route::apiResource('carDeal', carController::class);
    
});
