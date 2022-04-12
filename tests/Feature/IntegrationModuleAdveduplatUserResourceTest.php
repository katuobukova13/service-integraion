<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IntegrationModuleAdveduplatUserResourceTest extends TestCase
{
  use WithFaker;

//  public function testAdveduplatUsersEndPoint(): void
//  {
//    $resource = App::make(UserResource::class);
//
//    $this->assertEquals('http://localhost:5000/api/admin/users', $resource->endpoint);
//  }
//
//  public function testAdveduplatDataJson(): void
//  {
//    $resource = App::make(UserResource::class);
//
//    $this->assertEquals('JSON', $resource->dataType->name);
//  }
//
//  public function testAdveduplatFetchGET(): void
//  {
//    $id = 1;
//
//    $resource = App::make(UserResource::class)->fetch($id);
//
//    $this->assertIsArray($resource);
//    $this->assertEquals($resource['id'], $id);
//  }
//
//  public function testAdveduplatFetchPOST(): void
//  {
//    $email = $this->faker->email;
//    $password = $this->faker->password;
//
//    $resource = App::make(UserResource::class)->fetch('', options: [
//      'method' => 'POST',
//      'body' => [
//        'email' => $email,
//        'password' => $password
//      ]]);
//
//    $this->assertIsArray($resource);
//    $this->assertEquals($email, $resource['email']);
//  }
//
//  public function testAdveduplatFetchPUT(): void
//  {
//    $name = $this->faker->firstName();
//    $id = 7;
//
//    $resource = App::make(UserResource::class)->fetch($id, options: [
//      'method' => 'PUT',
//      'body' => [
//        'name' => $name
//      ]]);
//
//    $this->assertIsArray($resource);
//    $this->assertEquals($name, $resource['name']);
//  }
}
