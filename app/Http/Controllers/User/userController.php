<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\userRequest;
use App\Http\Requests\userUpdateRequest;
use App\Http\Resources\carResource;
use App\Http\Resources\userResource;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

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

    public function me(Request $request)
    {   
        $user = User::where('id',auth()->id())->get();
         return userResource::collection($user);
    }

    public function updateProfile(userUpdateRequest $request, $id)
    {

        $input = $request->validated();
        $user = User::findOrFail($id);
        $user->update($input);
        return response()->json(['message' => 'profile is updated successfully']);

    }

    public function changePassword(Request $request)
{
    
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8',
    ]);

    $user = auth()->user();

    if (! Hash::check($request->current_password, $user->password)) {
        return response()->json(['message' => 'The provided current password does not match our records.'], 422);
    }

    // Update the password
    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json(['message' => 'Password updated successfully.']);
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
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user_type'=>2,
        ]);
    }

}