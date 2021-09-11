<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthRequest $request) {
        $credentials = $request->only(['user_id','password']);

        if(!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized User!'], 401);
        }

        $user = $request->user();
        if($user->role == 'admin') {
            $tokenData = $user->createToken('My EMS', ['is_admin']);
        } else {
            $tokenData = $user->createToken('My EMS', ['is_user']);
        }
        $token = $tokenData->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        if ($token->save()) {
            return response()->json([
                'user' => $user,
                'access_token' => $tokenData->accessToken,
                'token_scope' => $tokenData->token->scopes[0],
                'expires_at' => Carbon::parse($tokenData->token->expires_at)->toDateTimeString()
            ], 200);
        } else {
            return response()->json([
                'message' => "Some error occurred, Please try again!",
            ], 500);
        }
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logout Successfully!'
        ], 200);
    }
}
