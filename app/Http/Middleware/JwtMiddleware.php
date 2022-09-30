<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {


            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            $newToken = JWTAuth::parseToken()->refresh();
            return response()->json([
                'success' => false,
                'status' => 'Token is Expired',
                'token' => $newToken
            ], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is Invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is Absent'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 200);
        }
        return $next($request);
    }
}