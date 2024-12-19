<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\userRequest;
use App\Http\Requests\userUpdateRequest;
use App\Http\Resources\carResource;
use App\Http\Resources\userResource;
use App\Models\Admin;
use App\Models\Car;
use App\Models\Dealership;
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

        if (! $token = auth($this->getGaurd($credentials['email']))->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token,$this->getGaurd($credentials['email']));
    }

    public function me()
    {   
        $user = User::where('id',auth()->id())->get();
         return userResource::collection($user);
    }

    public function updateProfile(userUpdateRequest $request)
    {
        $input = $request->validated();
        $user = User::findOrFail(auth('api')->id());
        if ($input['phoneNumber'] !== $user->phoneNumber) {
            $isPhoneUnique = !User::where('phoneNumber', $input['phoneNumber'])->exists() &&
                             !Dealership::where('phoneNumber', $input['phoneNumber'])->exists();
    
            if (!$isPhoneUnique) {
                return response()->json([
                    'message' => 'Validation errors',
                    'errors' => ['phoneNumber' => ['The phone number has already been taken.']]
                ], 422);
            }
        }
        $user->update($input);
        return response()->json(['message' => 'profile is updated successfully']);
    }

    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required', 'string' , 'min:12' , 'max:32'],
        'newPassword' => ['required' ,'string' , 'min:12' , 'max:32' , 'confirmed'],
    ]);
    $user = User::findOrFail(auth('api')->id());
    if (! Hash::check($request->current_password, $user->password)) {
        return response()->json(['message' => 'The provided current password does not match our records.'], 422);
    }
    $user->password = Hash::make($request->newPassword);
    $user->save();
    return response()->json(['message' => 'Password updated successfully.']);
}

     public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }



    protected function respondWithToken($token,$guard)
    {$userType = 0;
        if($guard==='api')
        {
            $userType = 1;
        }else if($guard==='dealership')
        {
            $userType = 2;
        }else
        {
            $userType = 3;
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($guard)->factory()->getTTL() * 60,
            'User-type'=>$userType,
        ]);
    }

    public function forgetPassword(Request $request)
    {
        $input = $request->validate([
            'email' => ['required', 'email']
        ]);
        $user = User::where('email' , $input)->first();
        if(!$user)
        {
            return response()->json(['message' => 'user not found']);
        }

        $otp = rand(10000,99999);
        $user->otp =$otp;
        $user->save();
        return response()->json(['message' => 'success!  '.$otp]);
    }

    private function getGaurd($email)
    {
        if(User::where('email',$email)->first())
        {
            return 'api';
        }
        else if(Dealership::where('email',$email)->first())
        {
            return 'dealership';
        }
        else if(Admin::where('email',$email)->first())
        {
            return 'admin';
        }
    }

}