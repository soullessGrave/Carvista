<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\favourateRequest;
use App\Http\Resources\favourateResource;
use App\Models\Favourate;

class favourateController extends Controller
{
    
    public function index()
    {
        $favCars = Favourate::where('userId',auth('api')->id())->orderBy('created_at','desc')->with('car')->get();
       return favourateResource::collection($favCars);
    }

    
    public function store(favourateRequest $request)
    {
        $input = $request->validated();
        $favCars = Favourate::where('userId',auth('api')->id())->where('carId',$input['carId'])->first();

        if(!$favCars)
        { 
           $input['userId'] = auth('api')->id();
           Favourate::create($input);
           return response()->json(['message' => 'Car Deal is added to Favourates List']);
        }

        return response()->json(['message' => 'Car deal already exists']);

    }

    
    public function destroy(string $id)
    {

        $car = Favourate::where('carId', $id)->where('userId', auth('api')->id())->firstOrFail();
        $car->delete();
        return response()->json(['message' => 'Car Deal is removed from Favourates List']);
        
    }
}
