<?php

namespace Tests\Feature;

use App\Http\Controllers\AmocrmContactController;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IntegrationModuleAmocrmContactControllerTest extends TestCase
{
  use WithFaker;

  public function testStore(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $response = $this->post('/api/v1/amocrm/contacts', [
      'first_name' => $contactFirstName,
      'last_name' => $contactLastName,
      'phones' => $contactPhone,
      'emails' => $contactEmail
    ]);

    $this->assertEquals($response['first_name'], $contactFirstName);
    $this->assertEquals($response['last_name'], $contactLastName);
    $this->assertEquals($response['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($contactEmail[0], $response['emails'][0]);
    $this->assertEquals($contactPhone[0], $response['phones'][0]);
  }

  public function testShow(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $responsePost = $this->post('/api/v1/amocrm/contacts', [
      'first_name' => $contactFirstName,
      'last_name' => $contactLastName,
      'phones' => $contactPhone,
      'emails' => $contactEmail
    ]);

    $id = $responsePost['id'];

    $response = $this->get('/api/v1/amocrm/contacts/' . $id);

    $this->assertEquals($id, $response['id']);
  }

  public function testUpdate(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $contactLastNameUpdate = $this->faker->lastName;

    $responsePost = $this->post('/api/v1/amocrm/contacts', [
      'first_name' => $contactFirstName,
      'last_name' => $contactLastName,
      'phones' => $contactPhone,
      'emails' => $contactEmail
    ]);

    $id = $responsePost['id'];

    $responseUpdate = $this->put('/api/v1/amocrm/contacts/' . $id, [
      'last_name' => $contactLastNameUpdate,
    ]);

    $this->assertEquals($responseUpdate['id'], $responsePost['id']);
    $this->assertEquals($contactLastNameUpdate, $responseUpdate['last_name']);
  }

  public function testListFilter(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone1[] = $this->faker->phoneNumber;
    $contactEmail1[] = $this->faker->email;
    $contactPhone2[] = $this->faker->phoneNumber;
    $contactEmail2[] = $this->faker->email;

    $contact1 = $this->post(action(
      [AmocrmContactController::class, 'store'],
      [
        'first_name' => $contactFirstName,
        'last_name' => $contactLastName,
        'phones' => $contactPhone1,
        'emails' => $contactEmail1
      ]));

    $contact2 = $this->post(action(
      [AmocrmContactController::class, 'store'],
      [
        'first_name' => $contactFirstName,
        'last_name' => $contactLastName,
        'phones' => $contactPhone2,
        'emails' => $contactEmail2
      ]));

    $listFilter = $this->get(action(
      [AmocrmContactController::class, 'index'],
      ['filter' => ['emails' => [
        $contact1['emails'],
        $contact2['emails']]]]));

    $this->assertCount(2, $listFilter->json());
    $this->assertEquals($contact1['emails'][0],
      $listFilter[0]['emails'][0]);
    $this->assertEquals($contact2['emails'][0],
      $listFilter[1]['emails'][0]);
  }

  public function testListLimit()
  {
    $listLimit = $this->get(action(
      [AmocrmContactController::class, 'index'],
      ['limit' => 2]));

    $this->assertCount(2, $listLimit->json());
  }

  public function testListPage()
  {
    $listPage = $this->get(action(
      [AmocrmContactController::class, 'index'],
      ['page' => 1]));

    $this->assertIsArray($listPage->json());
  }
}
