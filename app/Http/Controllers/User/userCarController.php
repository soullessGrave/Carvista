<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\carResource;
use App\Http\Resources\dealershipResource;
use App\Http\Resources\infoResource;
use App\Models\Car;
use App\Models\Dealership;
use Illuminate\Http\Request;

class userCarController extends Controller
{
    
    public function index()
    {
        $cars = Car::orderBy('created_at','desc')->get();
        return carResource::collection($cars);
    }

    public function show(string $id)
    {
        $car = Car::with('dealership')->findOrFail($id);
        return new carResource($car);
    }

    public function carSearch(Request $request)
{
    $query = Car::query();
    if ($request->has('name')) {
        $query->where('brandName', 'LIKE', $request->input('name') . '%')
        ->orWhere('modelName', 'LIKE',  $request->input('name') . '%')
        ->orWhere('manufactureYear', 'LIKE',  $request->input('name') .'%')
        ->orWhere('condition', 'LIKE', $request->input('name').'%')->with('dealership');
    }

    $searchResult = $query->get();
    return carResource::collection($searchResult);
}

public function dealershipProfile($id)
{

    $dealership = Dealership::findOrFail($id);
    return new DealershipResource($dealership);

}

public function dealershipCars($id)
    {
        $cars = Car::where('dealershipId',$id)->with('dealership')->get();
        return carResource::collection($cars);
    }

    public function dealershipInfo($id)
{
    $dealership = Dealership::findOrFail($id);
    return new infoResource($dealership);
}

}