<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password')))
        {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
            
        }else{
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'You are logged out'], 200);
    }

}
