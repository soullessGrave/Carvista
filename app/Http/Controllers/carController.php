<?php

namespace App\Http\Controllers;

use App\Http\Requests\carRequest;
use App\Models\Car;
use App\Models\User;
use App\Models\UserCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class carController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = UserCar::all();
        return response()->json(['data' => $cars]);
    }

    public function showUserCars()
    {
        $cars = UserCar::where('ownerId',auth('api')->id())->get();
        return response()->json(['data' => $cars]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function userCarStore(carRequest $request)
    {
        $input = $request->validated();
        $input['price'] = number_format($input['price']);
        $input['ownerId'] = auth('api')->id();
        UserCar::create($input);
        return response()->json(['message'=>'car deal added successfuly!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = UserCar::findOrFail($id);
        return response()->json(['data' => $car]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(carRequest $request, string $id)
    {
        $input = $request->validated();
        $car = UserCar::findOrFail($id);
        $input['price'] = number_format($input['price']);
        $car->update($input);
        return response()->json(['message'=>'car deal updated successfuly!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = Car::findOrFail($id);
        $car->delete();
        return response()->json(['message'=>'car deal deleted successfuly!']);
    }
}