<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SanctumController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // create token
        $token = $user->createToken("personal access token")->plainTextToken;
        $user->token = $token;
        return response()->json(['user' => $user]);
    }

    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)){
            $user = User::where("email",$request->email)->first();
//            $request->authenticate();
            $token = $user->createToken("personal access token")->plainTextToken;
            $user->authenticated($request,$user);
//            return Auth::user();
            $user->token = $token;
            return response()->json(["user"=>$user]);
        }

        return response()->json(["user"=> "These credentials do not match our records."]);
    }

    public function logout(Request $request){
        if ($request->user()->currentAccessToken()->delete()){
            return response()->json(['msg' => "You have been successfully logged out!"]);
        }
        return response()->json(['msg' => "some thing went wrong"]);
    }
    public function verify(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = Auth::user();

        if($request->input('two_factor_code') == $user->two_factor_code)
        {
            $user->resetTwoFactorCode();

            return response()->json(['message' => 'Code verified successfully.'], 200);
        }

        return response()->json(['message' => 'Invalid verification code.'], 400);
    }
    public function hello()
    {
        return Auth::user();
    }

}
