<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetcourseUserRequest;
use App\Modules\Integration\Domain\Getcourse\User\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Mosquitto\Exception;

class GetcourseUserController extends Controller
{
  public function store(GetcourseUserRequest $request)
  {
    $validatedRequest = $request->validated();

    try {
      $gcUser = App::make(UserModel::class)->set(array_merge(
        !empty($validatedRequest['email']) ? ['email' => $validatedRequest['email']] : [],
        !empty($validatedRequest['phone']) ? ['phone' => $validatedRequest['phone']] : [],
        !empty($validatedRequest['first_name']) ? ['first_name' => $validatedRequest['first_name']] : [],
        !empty($validatedRequest['last_name']) ? ['last_name' => $validatedRequest['last_name']] : [],
      ), [
        'refresh_if_exists' => 0,
      ]);
    } catch (Exception $e) {
      return response([
        'error' => true,
        'message' => $e->getMessage(),
      ]);
    }

    $gcUserId = $gcUser['result']['user_id'];

    return response(array_merge(
      $validatedRequest,
      [
        'id' => $gcUserId,
        'created_at' =>  $gcUser->created_at,
        'updated_at' =>  $gcUser->updated_at,
      ]
    ));
  }

}
