<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class OptionalAuthMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        try {
            if ($request->bearerToken()) {
                // Attempt to authenticate the user
                $user = JWTAuth::parseToken()->authenticate();

                if ($user) {
                    Auth::setUser($user);
                }
            }
         } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['msg' => 'Token is Invalid', 'code' => 407]);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['msg' => 'Token is Expired', 'code' => 408]);
            } else {
                return response()->json(['msg' => 'Authorization Token not found', 'code' => 401]);
            }
        }

        return $next($request);
    }
}


