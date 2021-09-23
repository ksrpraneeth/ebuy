<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register(UserRegRequest $request)
    {
        $request = $request->all();
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request)->toArray();
        return response()->json(['data'=>$user,'status'=>true]);
    }
}
