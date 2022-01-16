<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticateController extends Controller
{

    public function login(LoginRequest $request)
    {
      $user = User::where('email', $request->email)->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
         return response()->json(['message' => 'Login invalid'], 503);
      }
      return $user->createToken($request->email)->plainTextToken;
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

}
