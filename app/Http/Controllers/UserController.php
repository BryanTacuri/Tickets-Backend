<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;



class UserController extends Controller
{
    /**
     * Create a new UserController instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('jwtauth', ['except' => ['login', 'register']]);
    }




    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data =  $this->respondWithToken($token)->original + ['user' => auth()->user()];
        return $data;
        /* return response()->json($data);

        return $data = [,
            'user' => auth()->user()
        ]; */
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function prueba()
    {
        return "Hola desde el backend";
    }

    public function checkToken(Request $request)
    {

        try {
            $token = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            $newToken = JWTAuth::parseToken()->refresh();
            $this->setDataCorrect($newToken, 'Token is Expired', 200);
        } catch (TokenInvalidException $e) {
            $this->setError('Token is Invalid', 401);
        } catch (JWTException $e) {
            $this->setError('Token is Missing', 401);
        } catch (Exception $e) {
            $this->setError('Something went wrong', 500);
        }

        return $this->returnData();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:3',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validate(),
            ['password' => Hash::make($request->password)]


        ));

        return response()->json([
            'message' => '¡Usuario registrado exitosamente!',
            'user' => $user
        ], 201);
    }
}