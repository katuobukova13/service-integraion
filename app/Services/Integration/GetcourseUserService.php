<?php

namespace App\Services\Integration;

use App\Modules\Integration\Domain\Getcourse\User\UserModel;
use Throwable;

class GetcourseUserService
{
  /**
   * @param string $email
   * @param string|null $firstName
   * @param string|null $lastName
   * @param string|null $city
   * @param string|null $country
   * @param string|null $phone
   * @param array $group
   * @return array
   * @throws Throwable
   */
  public function createOrUpdate(
    string $email,
    string $firstName = null,
    string $lastName = null,
    string $city = null,
    string $country = null,
    string $phone = null,
    array  $group = [],
  ): array
  {
    $contact = UserModel::createOrUpdate([
      'email' => $email,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'city' => $city,
      'country' => $country,
      'phone' => $phone,
      'group' => $group,
    ]);

    return $contact->attributes;
  }
}
