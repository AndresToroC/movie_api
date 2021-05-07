<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $user = User::whereEmail($request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('token')->accessToken;

                return response()->json([
                    'user' => $user,
                    'token' => $token,
                    'message' => 'Successful login'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Incorrect password'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'User does not exist'
            ], 422);
        }
    }

    public function register(Request $request) {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users',
            'password' => 'required|confirmed|min:8'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $request['password'] = Hash::make($request->password);
        $user = User::create($request->all());

        $token = $user->CreateToken('token')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'User successfully registered'
        ], 200);
    }

    public function logout() {
        $token = Auth::guard('api')->user()->token();
        $token->revoke();

        return response()->json([
            'message' => 'Successful logout'
        ], 200);
    }
}
