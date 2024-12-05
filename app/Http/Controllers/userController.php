<?php

namespace App\Http\Controllers;

use App\Http\Requests\userRequest;
use App\Models\User;
use Illuminate\Http\Request;

class userController extends Controller
{

    public function register(userRequest $request)
     {
        $input = $request->validated();

        $user = User::where('email',$input['email'])->first();

        if($user)
        {
            return response()->json(['message' => 'account already exist']);
        }

        User::create($input);
        return response()->json(['message' => 'account registered successfuly!']);

     }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

     public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

     protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

}
