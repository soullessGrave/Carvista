<?php

namespace App\Http\Controllers\Dealership;

use App\Http\Controllers\Controller;
use App\Http\Requests\carRequest;
use App\Http\Resources\carResource;
use App\Models\Car;
use Illuminate\Http\Request;

class carController extends Controller
{

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

    public function index()
    {
        $cars = Car::where('dealershipId',auth('dealership')->id())->orderBy('created_at','desc')->with('dealership')->get();
        return carResource::collection($cars);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(carRequest $request)
    {
        $input = $request->validated();
        $input['price'] = number_format($input['price']);
        // $input['distance'] = number_format($input['distance']);
        $input['dealershipId'] = auth('dealership')->id();
        Car::create($input);
        return response()->json(['message'=>'car deal added successfuly!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = Car::with('dealership')->findOrFail($id);
        return new carResource($car);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(carRequest $request, string $id)
    {
        $input = $request->validated();
        $car = Car::findOrFail($id);
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
