<?php

namespace App\Http\Controllers\Api\Auth;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        Validator::make($request->all(), [
            'password' => ['required', 'string'],
            'email' => ['required', 'string', 'email']
        ])->validate();
        $credentials = $request->all(['email', 'password']);
        try {
            if (!$token = auth('api')->attempt($credentials)) {
                $message = new ApiMessages('Access Unauthorized');
                return response()->json($message->getMessage(), 401);
            }
            return response()->json([
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function logout()
    {
        try {
            if (auth('api')->user()) {
                auth('api')->logout();
                return response()->json(['Logout'], 200);
            } else {
                $message = new ApiMessages('Access Unauthorized');
                return response()->json($message->getMessage(), 401);
            }
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function refresh()
    {
        try {
            if (auth('api')->user()) {
                $token = auth('api')->refresh();
                return response()->json([
                    'token' => $token
                ], 200);
            } else {
                $message = new ApiMessages('Access Unauthorized');
                return response()->json($message->getMessage(), 401);
            }
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
