<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken(time())->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        Auth::guard('web')->logout();

        return response()->json([
            'message' => 'Token deleted successfully!'
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'phone' => ['string', 'max:20', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $result = [
            'status' => 500,
            'message' => 'Unexpected Error'
        ];

        try {
            $data['password'] = bcrypt($data['password']);
            $user = new User($data);

            if ($user->save()) {
                event(new Registered($user));
                $result = [
                    'status' => 200,
                    'user' => $user,
                    'token' => $user->createToken(time())->plainTextToken
                ];
            }
        } catch (Exception $exc) {
            $result = [
                'status' => 500,
                'error' => 'Unexpected Error: ' . $exc->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
}
