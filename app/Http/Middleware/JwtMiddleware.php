<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    public function handle($request, Closure $next)
    {
        try {
            $token = JWTAuth::parseToken();
            $user = $token->authenticate();


            // التحقق من uuid
            $payload = $token->getPayload();
            if (!$user || $user->auth_uuid !== $payload->get('auth_uuid')) {
                return response()->json(['msg' => 'Invalid user authentication', 'status' => 401]);
            }

        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['msg' => 'Token is Invalid', 'status' => 407]);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['msg' => 'Token is Expired', 'status' => 408]);
            } else {
                return response()->json(['msg' => 'Authorization Token not found', 'status' => 401]);
            }
        }

        return $next($request);
    }
}