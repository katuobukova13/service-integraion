<?php

namespace Tests\Feature;

use App\Services\Integration\AmocrmContactService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IntegrationModuleAmocrmContactServiceTest extends TestCase
{
  use WithFaker;

  public function testCreate(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $contact = $amocrmContactService->create(
      firstName: $contactFirstName,
      lastName: $contactLastName,
      phone: $contactPhone,
      email: $contactEmail,
    );

    $this->assertIsArray($contact);
    $this->assertEquals($contact['first_name'], $contactFirstName);
    $this->assertEquals($contact['last_name'], $contactLastName);
    $this->assertEquals($contact['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($contact["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.email'));
    $this->assertContains($contactEmail[0], $contact["custom_fields_values"][0]['values'][0]);
    $this->assertEquals($contact["custom_fields_values"][1]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($contactPhone[0], $contact["custom_fields_values"][1]['values'][0]);
  }

  public function testFind(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $order = $amocrmContactService->create(
      firstName: $contactFirstName,
      lastName: $contactLastName,
      phone: $contactPhone,
      email: $contactEmail,
    );

    $contact = $amocrmContactService->find($order['id']);

    $this->assertIsArray($contact);
    $this->assertEquals($order['id'], $contact['id']);
  }

  public function testUpdate(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;
    $firstNameUpdate = $this->faker->firstName;
    $phonesUpdate[] = $this->faker->phoneNumber;

    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $contact = $amocrmContactService->create(
      firstName: $contactFirstName,
      lastName: $contactLastName,
      phone: $contactPhone,
      email: $contactEmail,
    );

    $contactId = $contact['id'];

    $contactUpdated = $amocrmContactService->update(
      id: $contactId,
      firstName: $firstNameUpdate,
      phone: $phonesUpdate
    );

    $this->assertIsArray($contactUpdated);
    $this->assertEquals($firstNameUpdate, $contactUpdated['first_name']);
    $this->assertEquals($contactUpdated["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.email'));
    $this->assertContains($contactEmail[0], $contactUpdated["custom_fields_values"][0]['values'][0]);
    $this->assertEquals($contactUpdated["custom_fields_values"][1]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($phonesUpdate[0], $contactUpdated["custom_fields_values"][1]['values'][0]);
    $this->assertContains($contactPhone[0], $contactUpdated["custom_fields_values"][1]['values'][1]);
  }

  public function testListFilterContacts(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;
    $phones1[] = $this->faker->phoneNumber;
    $email1[] = $this->faker->email;
    $phones2[] = $this->faker->phoneNumber;
    $email2[] = $this->faker->email;

    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $contact1 = $amocrmContactService->create(
      firstName: $firstName,
      lastName: $lastName,
      phone: $phones1,
      email: $email1,
    );

    $contact2 = $amocrmContactService->create(
      firstName: $firstName,
      lastName: $lastName,
      phone: $phones2,
      email: $email2,
    );

    $listFilter = $amocrmContactService->list(filter: ['id' => [
      $contact1['id'], $contact2['id']]]);

    $this->assertIsArray($listFilter);
    $this->assertEquals(2, $listFilter['contacts']->count());
    $this->assertEquals($listFilter['contacts'][0]->attributes['id'], $contact1['id']);
    $this->assertEquals($listFilter['contacts'][1]->attributes['id'], $contact2['id']);
  }

  public function testListLimitContacts()
  {
    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $listLimit = $amocrmContactService->list(limit: 5);

    $this->assertIsArray($listLimit);
    $this->assertEquals(5, $listLimit['contacts']->count());

    $listPage = $amocrmContactService->list(page: 1);
    $this->assertIsArray($listPage);

    $listWith = $amocrmContactService->list(with: ['leads']);
    $this->assertIsArray($listWith);
  }

  public function testListPageContacts()
  {
    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $listPage = $amocrmContactService->list(page: 1);
    $this->assertIsArray($listPage);

    $listWith = $amocrmContactService->list(with: ['leads']);
    $this->assertIsArray($listWith);
  }

  public function testListWithContacts()
  {
    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $listWith = $amocrmContactService->list(with: ['leads']);
    $this->assertIsArray($listWith);
  }
}
