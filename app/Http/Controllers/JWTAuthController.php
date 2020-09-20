<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\ValidateLogin;
use App\Http\Requests\ValidateRegistration;
use App\Http\Resources\UserDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class JWTAuthController extends Controller
{
    public function register(ValidateRegistration $request)
    {
        (new RegisterController)->register($request);
        
        $user = Auth::user();
        $token = $this->guard()->login($user);
        return response()->json([
            'message' => "Registration was successful. And user has been logged in", 
            'token' => $token
        ], 201);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'], 
            'email' => $data['email'], 
            'password' => Hash::make($data['password'])
        ]);
    }

    public function login(ValidateLogin $request)
    {
        if (!$token = $this->guard()->attempt($request->only('email', 'password'))){
            return response()->json([
                'error' => 'Authentication Failed'
            ], 401);
        }
        
        return response()->json([
            'token' => $token
        ])->header(
            'Authorization', "Bearer $token"
        );
    }

    public function me(Request $request)
    {
        return new UserDetails(Auth::user());
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        return response()->json([
            'msg' => "Signed out"
        ]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}
