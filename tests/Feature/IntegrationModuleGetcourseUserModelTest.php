<?php

namespace Tests\Feature;

use App\Modules\Integration\Domain\Getcourse\User\UserModel;
use Illuminate\Foundation\Testing\WithFaker;
use League\Flysystem\Exception;
use Tests\TestCase;
use Throwable;

class IntegrationModuleGetcourseUserModelTest extends TestCase
{
  use WithFaker;

  /**
   * @throws Exception
   * @throws Throwable
   */
  public function testCreateOrUpdate(): void
  {
    $email = "teskkik@testik.ru";
    $group[] = "test";
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;

    $model = UserModel::createOrUpdate([
      "first_name" => $firstName,
      "last_name" => $lastName,
      "email" => $email,
      "group" => $group
    ]);

    $this->assertInstanceOf(UserModel::class, $model);
    $this->assertEquals($firstName, $model->attributes['first_name']);
    $this->assertEquals($lastName, $model->attributes['last_name']);
    $this->assertEquals($email, $model->attributes['email']);
    $this->assertEquals($group, $model->attributes['group']);
  }
}
