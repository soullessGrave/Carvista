<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\favourateRequest;
use App\Http\Resources\favourateResource;
use App\Models\Car;
use App\Models\Favourate;
use Illuminate\Http\Request;

class favourateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favCars = Favourate::where('userId',auth('api')->id())->orderBy('created_at','desc')->with('car')->get();
       return favourateResource::collection($favCars);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $car = Favourate::where('carId', $id)->where('userId', auth('api')->id())->firstOrFail();
        $car->delete();
        return response()->json(['message' => 'Car Deal is removed from Favourates List']);
        
    }
}
