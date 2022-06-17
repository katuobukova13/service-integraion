<?php

namespace Tests\Feature;

use App\Http\Requests\Getcourse\User\UserStoreRequest;
use App\Services\Integration\GetcourseUserService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Throwable;

class IntegrationModuleGetcourseUserControllerTest extends TestCase
{
  use WithFaker;

  /**
   * @throws Throwable
   */
  public function testStore(): void
  {
    $email = "teskkik@testik.ru";
    $phone = $this->faker->phoneNumber;
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;
    $country = $this->faker->country;
    $city = $this->faker->city;

    $user = UserStoreRequest::create('users', 'POST',
      App::make(GetcourseUserService::class)->createOrUpdate(
        email: $email,
        phone: $phone,
        firstName: $firstName,
        lastName: $lastName,
        country: $country,
        city: $city,
      ));
    
    $this->assertEquals($phone, $user['phone']);
    $this->assertEquals($firstName, $user["first_name"]);
    $this->assertEquals($lastName, $user["last_name"]);
    $this->assertEquals($email, $user['email']);
    $this->assertEquals($country, $user['country']);
    $this->assertEquals($city, $user['city']);
  }
}
