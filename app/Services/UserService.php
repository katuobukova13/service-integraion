<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
  public function create(User $user, array $data)
  {
    return $user->create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password'])
    ]);
  }

  public function createToken(User $user, array $data): string
  {
    return $user
      ->createToken($data['name'])
      ->plainTextToken;
  }
}
