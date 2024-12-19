<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dealership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth($this->getGaurd($credentials['email']))->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token,$this->getGaurd($credentials['email']));
    }

    public function forgetPassword(Request $request)
{
    $input = $request->validate([
        'email' => ['required', 'email']]);
    
    $user = User::where('email', $input['email'])->first();

    $dealership = Dealership::where('email', $input['email'])->first();
    
    if (!$user && !$dealership) {
        return response()->json(['message' => 'Email not found, check your email'], 404);
    } 
    else if ($user) {
        return $this->otpGenerator($user);
    } 
    else if ($dealership) {
        return $this->otpGenerator($dealership);
    }
}

public function verifyOtp(Request $request)
{
    $input = $request->validate([
        'email' => ['required','email'],
        'otp' => ['required','numeric','min:4'],
    ]);

    $user = User::where('email', $input['email'])->where('otp', $input['otp'])->first();
    $dealership = Dealership::where('email', $input['email'])->where('otp', $input['otp'])->first();
    if(!$user && !$dealership)
      {
        return response()->json(['message' => 'otp number is incorrect'], 404);
      }
      else if($user){
        return response()->json(['message' => 'user otp matched'],200);
        }   else if($dealership){
            return response()->json(['message' => 'dealership otp matched'],200);
            }   

}

public function resetPassword(Request $request)
    {
      $input = $request->validate([
        'email' => ['required','email'],
        'otp' => ['required','numeric'],
        'newPassword' =>['required', 'confirmed'],
      ]);

      $user = User::where('email', $input['email'])->where('otp', $input['otp'])->first();
      $dealership = Dealership::where('email', $input['email'])->where('otp', $input['otp'])->first();
      if(!$user && !$dealership)
      {
        return response()->json(['message' => 'account not found, check your otp code']);
      } 
      else if($user){
      $user->password=Hash::make($input['newPassword']);
      $user->otp=null;
      $user->save();
      return response()->json(['message' => 'password is reseted successfuly!']);
      }
      else if($dealership){
      $dealership->password=Hash::make($input['newPassword']);
      $dealership->otp=null;
      $dealership->save();
      return response()->json(['message' => 'password is reseted successfuly!']);
      }
      
    }

    // public function changePassword(Request $request)
    // {
    //   $input = $request->validate([
    //     'newPassword' =>['required', 'string' , 'min:12' , 'max:32', 'confirmed'],
    //   ]);

    //   $user = User::where('email', $input['email'])->where('otp', $input['otp'])->first();
    //   $dealership = Dealership::where('email', $input['email'])->where('otp', $input['otp'])->first();
    //   if(!$user && !$dealership)
    //   {
    //     return response()->json(['message' => 'account not found, check your otp code']);
    //   } 
    //   else if($user){
    //   $user->password=Hash::make($input['newPassword']);
    //   $user->otp=null;
    //   $user->save();
    //   return response()->json(['message' => 'password is reseted successfuly!']);
    //   }
    //   else if($dealership){
    //   $dealership->password=Hash::make($input['newPassword']);
    //   $dealership->otp=null;
    //   $dealership->save();
    //   return response()->json(['message' => 'password is reseted successfuly!']);
    //   }
      
    // }

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

    private function otpGenerator($record)
    {
        $otp = rand(999,9999);
        $record->otp =$otp;
        $record->save();
        return response()->json(['message' => 'success!  '.$otp], 200);
    }
    
}
