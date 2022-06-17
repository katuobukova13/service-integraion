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
      phones: $contactPhone,
      emails: $contactEmail,
    );

    $this->assertIsArray($contact);
    $this->assertEquals($contact['first_name'], $contactFirstName);
    $this->assertEquals($contact['last_name'], $contactLastName);
    $this->assertEquals($contact['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($contact['emails'][0], $contactEmail[0]);
    $this->assertEquals($contact['phones'][0], $contactPhone[0]);
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
      phones: $contactPhone,
      emails: $contactEmail,
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
      phones: $contactPhone,
      emails: $contactEmail,
    );

    $contactId = $contact['id'];

    $contactUpdated = $amocrmContactService->update(
      id: $contactId,
      firstName: $firstNameUpdate,
      phones: $phonesUpdate
    );

    $this->assertIsArray($contactUpdated);
    $this->assertEquals($firstNameUpdate, $contactUpdated['first_name']);
    $this->assertEquals($contactUpdated['emails'][0], $contactEmail[0]);
    $this->assertEquals($contactUpdated['phones'][0], $phonesUpdate[0]);
    $this->assertEquals($contactPhone[0], $contactUpdated['phones'][1]);
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
      phones: $phones1,
      emails: $email1,
    );

    $contact2 = $amocrmContactService->create(
      firstName: $firstName,
      lastName: $lastName,
      phones: $phones2,
      emails: $email2,
    );

    $listFilter = $amocrmContactService->list(filter: ['id' => [
      $contact1['id'], $contact2['id']]]);

    $this->assertIsArray($listFilter->all());
    $this->assertCount(2, $listFilter);
    $this->assertEquals($listFilter[0]->attributes['id'], $contact1['id']);
    $this->assertEquals($listFilter[1]->attributes['id'], $contact2['id']);
  }

  public function testListLimitContacts()
  {
    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $listLimit = $amocrmContactService->list(limit: 5);

    $this->assertIsArray($listLimit->all());
    $this->assertCount(5, $listLimit->all());

    $listPage = $amocrmContactService->list(page: 1);
    $this->assertIsArray($listPage->all());

    $listWith = $amocrmContactService->list(with: ['leads']);
    $this->assertIsArray($listWith->all());
  }

  public function testListPageContacts()
  {
    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $listPage = $amocrmContactService->list(page: 1);
    $this->assertIsArray($listPage->all());

    $listWith = $amocrmContactService->list(with: ['leads']);
    $this->assertIsArray($listWith->all());
  }
}
