<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->bearerToken()){
            return response()->json(['status'=>false,'message'=>'Authorization token not found'],401);
        }

        JWTAuth::setRequest($request);
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        }catch (\Exception $e){
            return response()->json(['status'=>false,'message'=>'Token is invalid'],401);
        }

        return $next($request);
    }
}
