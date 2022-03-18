<?php

namespace Tests\Feature;

use App\Modules\Integration\Domain\Adveduplat\User\UserModel;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IntegrationModuleAdveduplatUserModelTest extends TestCase
{
  use WithFaker;

  public function testCreatedUser()
  {
    $email = $this->faker->email;
    $password = $this->faker->password;
    $name = $this->faker->firstName . ' ' . $this->faker->lastName;

    $user = UserModel::create([
      'email' => $email,
      'password' => $password,
      'name' => $name
    ]);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Adveduplat\User\UserModel', $user);
    $this->assertEquals($email, $user->attributes['email']);
    $this->assertEquals($name, $user->attributes['name']);
  }

  /**
   * @throws Exception
   */
  public function testFindUser()
  {
    $email = $this->faker->email;
    $password = $this->faker->password;
    $name = $this->faker->firstName . ' ' . $this->faker->lastName;

    $userCreate = UserModel::create([
      'email' => $email,
      'password' => $password,
      'name' => $name
    ]);

    $userFind = UserModel::find($userCreate->attributes['id']);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Adveduplat\User\UserModel', $userFind);
    $this->assertEquals($userCreate->attributes['id'], $userFind->attributes['id']);
  }

  public function testUpdateUser(): void
  {
    $email = $this->faker->email;
    $password = $this->faker->password;
    $name = $this->faker->firstName . ' ' . $this->faker->lastName;

    $nameUpdate = $this->faker->firstName . ' ' . $this->faker->lastName;

    $model = UserModel::create(["name" => $name, "email" => $email, "password" => $password]);

    $user = $model->update([
      'id' => $model->attributes['id'],
      'name' => $nameUpdate
    ]);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Adveduplat\User\UserModel', $user);
    $this->assertEquals($nameUpdate, $user->attributes['name']);
  }
}
