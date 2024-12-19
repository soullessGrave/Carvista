<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\carResource;
use App\Http\Resources\dealershipResource;
use App\Http\Resources\userResource;
use App\Models\Car;
use App\Models\Dealership;
use App\Models\User;
use Illuminate\Http\Request;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function userIndex()
    {
        $users = User::orderBy('created_at','desc')->get();
        return userResource::collection($users);
    }

    public function dealershipIndex()
    {
        $dealerships = Dealership::orderBy('created_at','desc')->get();
        return dealershipResource::collection($dealerships);
    }

    public function carIndex()
    {
        $cars = Car::orderBy('created_at','desc')->get();
        return carResource::collection($cars);
    }

    public function dashboard()
    {
        $cars = Car::all()->count();
        $users = User::all()->count();
        $dealerships = Dealership::all()->count();
        return response()->json( 
        ['usersNumber' => $users,
         'carsNumber' => $cars,
         'dealershipsNumber' => $dealerships,
         ]);
    }

    public function logout()
    {
        auth('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

}
