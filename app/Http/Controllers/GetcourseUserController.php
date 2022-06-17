<?php

namespace App\Http\Controllers;

use App\Http\Requests\Getcourse\User\UserStoreRequest;
use App\Services\Integration\GetcourseUserService;
use Throwable;

class GetcourseUserController extends Controller
{
  /**
   * @throws Throwable
   */
  public function store(UserStoreRequest $request, GetcourseUserService $userService): array
  {
    $attributes = $request->validated();

    $user = $userService->createOrUpdate(
      email: $attributes['email'],
      firstName: $attributes['first_name'] ?? null,
      lastName: $attributes['last_name'] ?? null,
      city: $attributes['city'] ?? null,
      country: $attributes['country'] ?? null,
      phone: $attributes['phone'] ?? null,
      group: $attributes['group'] ?? []);

    $updatedOrCreatedUserFields = [];
    foreach ($user as $key => $value) {
      if ($value !== null && $value !== []) {
        $updatedOrCreatedUserFields[$key] = $value;
      }
    }

    return $updatedOrCreatedUserFields;
  }
}
