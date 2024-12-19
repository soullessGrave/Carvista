<?php

use App\Http\Controllers\Admin\adminController;
use App\Http\Controllers\Dealership\dealershipController;
use App\Http\Controllers\Dealership\carController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\User\favourateController;
use App\Http\Controllers\User\userCarController;
use App\Http\Controllers\User\userController;
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


Route::post('login', [loginController::class,'login']);
Route::post('forgetPassword', [loginController::class,'forgetPassword']);
Route::post('verifyOtp', [loginController::class,'verifyOtp']);
Route::post('resetPassword', [loginController::class,'resetPassword']);


Route::post('userRegister', [userController::class,'register']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

Route::get('userAccountDetails', [userController::class,'me']);
Route::put('updateUserProfile', [userController::class,'updateProfile']);
Route::post('changePassword', [userController::class,'changePassword']);
Route::get('userlogout', [userController::class,'logout']);

    Route::get('userCars', [userCarController::class,'index']);
    Route::get('carDetails/{id}', [userCarController::class,'show']);
    Route::post('search', [userCarController::class,'carSearch']);
    Route::get('dealershipCars/{id}', [userCarController::class,'dealershipCars']);
    Route::get('dealershipProfile/{id}', [userCarController::class,'dealershipProfile']);
    Route::get('dealershipInfo/{id}', [userCarController::class,'dealershipInfo']);


        Route::get('favourateList', [favourateController::class,'index']);
        Route::post('addToFavourates', [favourateController::class,'store']);
        Route::delete('removeFromFavourate/{id}', [favourateController::class,'destroy']);

});


Route::post('dealershipRegister', [dealershipController::class,'register']);

Route::group([

    'middleware' => 'auth:dealership',
    'prefix' => 'dealership'

], function ($router) {

Route::get('dealershipProfile', [dealershipController::class,'me']);
Route::put('updateProfile', [dealershipController::class,'updateProfile']);
Route::post('changePassword', [dealershipController::class,'changePassword']);
Route::get('dealershiplogout', [dealershipController::class,'logout']);

    Route::get('dealershipCars', [carController::class,'index']);
    Route::post('search', [carController::class,'carSearch']);
    Route::post('addCarDeal', [carController::class,'store']);
    Route::get('showCarDeal/{id}', [carController::class,'show']);
    Route::put('updateCarDeal/{id}', [carController::class,'update']);
    Route::delete('deleteCarDeal/{id}', [carController::class,'destroy']);
    
});

Route::group([

    'middleware' => 'auth:admin',
    'prefix' => 'admin'

], function ($router) {

    Route::get('allCars', [adminController::class,'carIndex']);
    Route::get('allDealerships', [adminController::class,'dealershipIndex']);
    Route::get('allUsers', [adminController::class,'userIndex']);
    Route::get('dashboard', [adminController::class,'dashboard']);
    Route::get('adminlogout', [adminController::class,'logout']);

});