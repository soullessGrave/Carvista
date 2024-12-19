<?php

namespace App\Http\Controllers\Dealership;

use App\Http\Controllers\Controller;
use App\Http\Requests\dealershipRequest;
use App\Http\Requests\dealershipUpdateRequest;
use App\Http\Resources\carResource;
use App\Http\Resources\dealershipResource;
use App\Models\Car;
use App\Models\Dealership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function me()
    {   
        $dealership = Dealership::where('id',auth('dealership')->id())->get();
         return dealershipResource::collection($dealership);
    }

    public function updateProfile(dealershipUpdateRequest $request)
    {

        $input = $request->validated();
        $user = Dealership::findOrFail(auth('dealership')->id());
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
    $dealership = Dealership::findOrFail(auth('dealership')->id());
    if (! Hash::check($request->current_password, $dealership->password)) {
        return response()->json(['message' => 'The provided current password does not match our records.'], 422);
    }
    $dealership->password = Hash::make($request->new_password);
    $dealership->save();
    return response()->json(['message' => 'Password updated successfully.']);
}

    public function logout()
    {
        auth('dealership')->logout();

        return response()->json(['message' => 'Successfully logged out']);
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
