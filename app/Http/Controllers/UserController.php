<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthRequest;
use App\Http\Requests\UserRegRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function register(UserRegRequest $request)
    {

        $request = $request->all();
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request);
        if ($user) {
            return response()->json(['status' => true, 'data' => $user]);
        }
    }

    public function login(UserAuthRequest $userAuthRequest)
    {
        $credentials = $userAuthRequest->all();
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials', 'status' => false], 401);
        }
        if (Auth::check()) {
            $user = Auth::user()->only('username');
            return response()->json(['status' => false, 'data' => ['token' => $token, 'user' => $user], 'message' => 'failed']);
        }
    }
}
