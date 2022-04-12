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

    $response = $this->post('/api/amocrm/contacts', [
      'first_name' => $contactFirstName,
      'last_name' => $contactLastName,
      'phone' => $contactPhone,
      'email' => $contactEmail
    ]);

    $this->assertEquals($response['first_name'], $contactFirstName);
    $this->assertEquals($response['last_name'], $contactLastName);
    $this->assertEquals($response['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($response["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.email'));
    $this->assertContains($contactEmail[0], $response["custom_fields_values"][0]['values'][0]);
    $this->assertEquals($response["custom_fields_values"][1]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($contactPhone[0], $response["custom_fields_values"][1]['values'][0]);
  }

  public function testShow(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $responsePost = $this->post('/api/amocrm/contacts', [
      'first_name' => $contactFirstName,
      'last_name' => $contactLastName,
      'phone' => $contactPhone,
      'email' => $contactEmail
    ]);

    $id = $responsePost['id'];

    $response = $this->get('/api/amocrm/contacts/' . $id);

    $this->assertEquals($id, $response['id']);
  }

  public function testUpdate(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $contactLastNameUpdate = $this->faker->lastName;

    $responsePost = $this->post('/api/amocrm/contacts', [
      'first_name' => $contactFirstName,
      'last_name' => $contactLastName,
      'phone' => $contactPhone,
      'email' => $contactEmail
    ]);

    $id = $responsePost['id'];

    $responseUpdate = $this->put('/api/amocrm/contacts/' . $id, [
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
        'phone' => $contactPhone1,
        'email' => $contactEmail1
      ]));

    $contact2 = $this->post(action(
      [AmocrmContactController::class, 'store'],
      [
        'first_name' => $contactFirstName,
        'last_name' => $contactLastName,
        'phone' => $contactPhone2,
        'email' => $contactEmail2
      ]));

    $listFilter = $this->get(action(
      [AmocrmContactController::class, 'index'],
      ['filter' => ['email' => [
        $contact1['custom_fields_values'][0]['values'][0]['value'],
        $contact2['custom_fields_values'][0]['values'][0]['value']]]]));

    $this->assertCount(2, $listFilter['contacts']);
    $this->assertContains($contact1['custom_fields_values'][0]['values'][0]['value'],
      $listFilter['contacts'][0]['attributes']['custom_fields_values'][0]['values'][0]);
    $this->assertContains($contact2['custom_fields_values'][0]['values'][0]['value'],
      $listFilter['contacts'][1]['attributes']['custom_fields_values'][0]['values'][0]);
  }

  public function testListLimit()
  {
    $listLimit = $this->get(action(
      [AmocrmContactController::class, 'index'],
      ['limit' => 2]));

    $this->assertCount(2, $listLimit['contacts']);
  }

  public function testListPage()
  {
    $listPage = $this->get(action(
      [AmocrmContactController::class, 'index'],
      ['page' => 1]));

    $this->assertIsArray($listPage['contacts']);
  }

  public function testListWith()
  {
    $listWith = $this->get(action(
      [AmocrmContactController::class, 'index'],
      ['with' => 'leads']));

    $this->assertIsArray($listWith['contacts']);
  }
}
