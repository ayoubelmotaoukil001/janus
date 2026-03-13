<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->validated();
        $user=User::where("email",$request->email)->first();
        if(!$user)
        {
            return response()->json(["message" =>"user not found"]  , 404)  ;
        }
    
        if (!Hash::check($request->password, $user->password))
            {
                return response()->json(['message'=> "password is incorrect "]  ,401) ;
            }

            $token = $user->createToken("auth_token")->plainTextToken ;
            return response()->json(['success' => true, 'data' => ['user' => $user, 'token' => $token], 'message' => 'Success'], 201);
        }

        public function register(RegisterRequest $request)
        {
            $request->validated() ;
            $user= User::create([
                'name' => $request->name  ,
                "email" =>$request->email ,
                "password" => Hash::make($request->password) 
            ]) ;
            
            
            $token = $user->createToken("auth_token")->plainTextToken ;
                
            return response()->json(["success" => true, "data" => ["user" => $user, "token" => $token], "message" => "Registration successful"], 201);        }

        public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete() ;
            return response()->json(['success' => true, 'data' => null, 'message' => 'Logged out'], 200);
        }
        public function me(Request $request)
        {
            return response()->json(['success' => true, 'data' => $request->user(), 'message' => 'User Info'], 200);
        }
}
