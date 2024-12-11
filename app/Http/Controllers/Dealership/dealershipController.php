<?php

namespace App\Http\Controllers\Dealership;

use App\Http\Controllers\Controller;
use App\Http\Requests\dealershipRequest;
use App\Models\Dealership;
use Illuminate\Http\Request;

class dealershipController extends Controller
{

    public function register(dealershipRequest $request)
     {
        $input = $request->validated();

        $user = Dealership::where('email',$input['email'])->first();

        if($user)
        {
            return response()->json(['message' => 'account already exist']);
        }

        Dealership::create($input);
        return response()->json(['message' => 'account registered successfuly!']);

     }

     public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('dealership')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('dealership')->factory()->getTTL() * 60,
            'user_type'=>1
        ]);
    }
    
}
