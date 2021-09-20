<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        $credentials = [
            'email' => request('email'), 
            'password' => request('password')
        ];
        if (Auth::attempt($credentials)) {
            $success['token'] = Auth::user()->createToken('AccessToken')->accessToken;
            return response()->json(['success' => $success]);
        }

        return response()->json(['error' => 'Unauthorised'], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $success['token'] = $user->createToken('AccessToken')->accessToken;
        return response()->json(['success' => $success]);
    }

    public function logout() {
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['success' => true]);
    }
}
