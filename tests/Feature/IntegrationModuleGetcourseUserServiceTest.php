<?php

namespace Tests\Feature;

use App\Services\Integration\GetcourseUserService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class IntegrationModuleGetcourseUserServiceTest extends TestCase
{
  use WithFaker;

  /**
   * @throws Throwable
   */
  public function testCreateOrUpdate(): void
  {
    $email = "teskkik@testik.ru";
    $group[] = "test";
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;

    $getcourseUserService = $this->app->make(GetcourseUserService::class);

    $user = $getcourseUserService->createOrUpdate(
      email: $email,
      firstName: $firstName,
      lastName: $lastName,
      group: $group,
    );

    $this->assertIsArray($user);
    $this->assertEquals($firstName, $user['first_name']);
    $this->assertEquals($lastName, $user['last_name']);
    $this->assertEquals($email, $user['email']);
    $this->assertEquals($group, $user['group']);
  }
}
