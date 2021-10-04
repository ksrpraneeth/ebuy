<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class JwtMiddleware


{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!$request->bearerToken()) {
            return response()->json(['status' => false, 'message' => 'Authorization Token not found'], 401);
        }

        JWTAuth::setRequest($request);

        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Token is Invalid'], 401);
            }
        } catch (\Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['status' => false, 'message' => 'Token is Invalid'], 401);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json(['status' => false, 'message' => 'Token is Expired'], 401);
            }else{
                return response()->json(['status' => false, 'message' => 'Token is Invalid'], 401);
            }
        }
        return $next($request);
    }
}
