<?php

namespace Tests\Feature;

use App\Services\Integration\AmocrmContactService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
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

    $amocrmContactService = App::make(AmocrmContactService::class);

    $contact = $amocrmContactService->create(
      firstName: $contactFirstName,
      lastName: $contactLastName,
      phone: $contactPhone,
      email: $contactEmail,
    );

    $this->assertIsArray($contact);
    $this->assertEquals($contact['contact']['first_name'], $contactFirstName);
    $this->assertEquals($contact['contact']['last_name'], $contactLastName);
    $this->assertEquals($contact['contact']['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($contact['contact']["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.email'));
    $this->assertContains($contactEmail[0], $contact['contact']["custom_fields_values"][0]['values'][0]);
    $this->assertEquals($contact['contact']["custom_fields_values"][1]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($contactPhone[0], $contact['contact']["custom_fields_values"][1]['values'][0]);
  }

  public function testFind(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $amocrmContactService = App::make(AmocrmContactService::class);

    $order = $amocrmContactService->create(
      firstName: $contactFirstName,
      lastName: $contactLastName,
      phone: $contactPhone,
      email: $contactEmail,
    );

    $contact = $amocrmContactService->find($order['contact']['id']);

    $this->assertIsArray($contact);
    $this->assertEquals($order['contact']['id'], $contact['contact']['id']);
  }

  public function testUpdate(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;
    $firstNameUpdate = $this->faker->firstName;
    $phonesUpdate[] = $this->faker->phoneNumber;

    $amocrmContactService = App::make(AmocrmContactService::class);

    $contact = $amocrmContactService->create(
      firstName: $contactFirstName,
      lastName: $contactLastName,
      phone: $contactPhone,
      email: $contactEmail,
    );

    $contactId = $contact['contact']['id'];

    $contactUpdated = $amocrmContactService->update(
      id: $contactId,
      firstName: $firstNameUpdate,
      phone: $phonesUpdate
    );

    $this->assertIsArray($contactUpdated);
    $this->assertEquals($firstNameUpdate, $contactUpdated['contact']['first_name']);
    $this->assertEquals($contactUpdated['contact']["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.email'));
    $this->assertContains($contactEmail[0], $contactUpdated['contact']["custom_fields_values"][0]['values'][0]);
    $this->assertEquals($contactUpdated['contact']["custom_fields_values"][1]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($phonesUpdate[0], $contactUpdated['contact']["custom_fields_values"][1]['values'][0]);
    $this->assertContains($contactPhone[0], $contactUpdated['contact']["custom_fields_values"][1]['values'][1]);
  }

  public function testListContacts(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;
    $phones1[] = $this->faker->phoneNumber;
    $email1[] = $this->faker->email;
    $phones2[] = $this->faker->phoneNumber;
    $email2[] = $this->faker->email;
    $phones3[] = $this->faker->phoneNumber;
    $email3[] = $this->faker->email;

    $amocrmContactService = App::make(AmocrmContactService::class);

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

    $contact3 = $amocrmContactService->create(
      firstName: $firstName,
      lastName: $lastName,
      phone: $phones3,
      email: $email3,
    );

    $listFilter = $amocrmContactService->list(filter: ['id' => [
      $contact1['contact']['id'], $contact2['contact']['id']]]);

    $this->assertIsArray($listFilter);
    $this->assertEquals(2, $listFilter['contacts']->count());
    $this->assertTrue($listFilter['contacts']->contains('id', $contact1['contact']['id']));
    $this->assertTrue($listFilter['contacts']->contains('id', $contact2['contact']['id']));
    $this->assertFalse($listFilter['contacts']->contains('id', $contact3['contact']['id']));

    $listLimit = $amocrmContactService->list(limit: 5);

    $this->assertIsArray($listLimit);
    $this->assertEquals(5, $listLimit['contacts']->count());

    $listPage = $amocrmContactService->list(page: 1);
    $this->assertIsArray($listPage);

    $listWith = $amocrmContactService->list(with: ['leads']);
    $this->assertIsArray($listWith);
  }
}
