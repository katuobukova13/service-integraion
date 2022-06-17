<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function register(Request $request): \Illuminate\Http\JsonResponse
  {
    $request->validate([
      'name' => 'required',
      'email' => 'required|email',
      'password' => 'required'
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);

    return response()->json($user);
  }

  public function login(Request $request): \Illuminate\Http\JsonResponse
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);

    $credential = request(['email', 'password']);
    if (!Auth::attempt($credential)) {
      return response()->json([
        'status' => false,
        'message' => 'The given data is invalid.',
      ], 401);
    }

    $user = User::where('email', $request->email)->first();
    $authToken = $user->createToken("API TOKEN")->plainTextToken;

    return response()->json([
      'status' => true,
      'token' => $authToken
    ], 200);
  }
}
