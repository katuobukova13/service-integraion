<?php

namespace Tests\Feature;

use App\Http\Requests\ContactRequest;
use App\Services\Integration\AmocrmContactService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
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

    $request = ContactRequest::create('/api/amocrm/contacts', 'POST',
      App::make(AmocrmContactService::class)->create(
        firstName: $contactFirstName,
        lastName: $contactLastName,
        phone: $contactPhone,
        email: $contactEmail
      ));

    $this->assertEquals($request->request->all()['contact']['first_name'], $contactFirstName);
    $this->assertEquals($request->request->all()['contact']['last_name'], $contactLastName);
    $this->assertEquals($request->request->all()['contact']['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($request->request->all()['contact']["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.email'));
    $this->assertContains($contactEmail[0], $request->request->all()['contact']["custom_fields_values"][0]['values'][0]);
    $this->assertEquals($request->request->all()['contact']["custom_fields_values"][1]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($contactPhone[0], $request->request->all()['contact']["custom_fields_values"][1]['values'][0]);
  }

  public function testShow(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $request = ContactRequest::create('/api/amocrm/contacts', 'POST', App::make(AmocrmContactService::class)->create(
      firstName: $contactFirstName,
      lastName: $contactLastName,
      phone: $contactPhone,
      email: $contactEmail
    ));

    $contact = ContactRequest::create('/api/amocrm/contacts', 'GET',
      App::make(AmocrmContactService::class)->find($request->request->all()['contact']['id']));

    $this->assertEquals($contact->query->all()['contact']['id'], $request->request->all()['contact']['id']);
  }

  public function testUpdate(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $contactLastNameUpdate = $this->faker->lastName;

    $request = ContactRequest::create('/api/amocrm/contacts', 'POST',
      App::make(AmocrmContactService::class)->create(
        firstName: $contactFirstName,
        lastName: $contactLastName,
        phone: $contactPhone,
        email: $contactEmail
      ));

    $contactUpdated = ContactRequest::create('/api/amocrm/contacts', 'PUT',
      App::make(AmocrmContactService::class)->update(
        id: $request->request->all()['contact']['id'],
        lastName: $contactLastNameUpdate,
      ));

    $this->assertEquals($contactUpdated->request->all()['contact']['id'], $request->request->all()['contact']['id']);
    $this->assertEquals($contactLastNameUpdate, $contactUpdated['contact']['last_name']);
  }

  public function testList(): void
  {
    $contactFirstName1 = $this->faker->firstName;
    $contactFirstName2 = $this->faker->firstName;
    $contactFirstName3 = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;

    $contact1 = ContactRequest::create('/api/amocrm/contacts', 'POST',
      App::make(AmocrmContactService::class)->create(
        firstName: $contactFirstName1,
        lastName: $contactLastName,
        phone: $contactPhone,
        email: $contactEmail
      ));

    $contact2 = ContactRequest::create('/api/amocrm/contacts', 'POST',
      App::make(AmocrmContactService::class)->create(
        firstName: $contactFirstName2,
        lastName: $contactLastName,
        phone: $contactPhone,
        email: $contactEmail
      ));

    $contact3 = ContactRequest::create('/api/amocrm/contacts', 'POST',
      App::make(AmocrmContactService::class)->create(
        firstName: $contactFirstName3,
        lastName: $contactLastName,
        phone: $contactPhone,
        email: $contactEmail
      ));

    $listFilter = ContactRequest::create('/api/amocrm/contacts', 'GET',
      App::make(AmocrmContactService::class)->list(
        filter: ['id' => [
          $contact1->request->all()['contact']['id'],
          $contact2->request->all()['contact']['id'],
          $contact3->request->all()['contact']['id']]]));

    $this->assertInstanceOf('App\Http\Requests\ContactRequest', $listFilter);
    $this->assertEquals(3, $listFilter->query->all()['contacts']->count());
    $this->assertTrue($listFilter->query->all()['contacts']->contains('id', $contact1->request->all()['contact']['id']));
    $this->assertTrue($listFilter->query->all()['contacts']->contains('id', $contact2->request->all()['contact']['id']));
    $this->assertTrue($listFilter->query->all()['contacts']->contains('id', $contact3->request->all()['contact']['id']));

    $listLimit = ContactRequest::create('/api/amocrm/contacts', 'GET',
      App::make(AmocrmContactService::class)->list(limit: 5));

    $this->assertInstanceOf('App\Http\Requests\ContactRequest', $listLimit);
    $this->assertEquals(5, $listLimit->query->all()['contacts']->count());

    $listPage = ContactRequest::create('/api/amocrm/contacts', 'GET',
      App::make(AmocrmContactService::class)->list(page: 1));

    $this->assertInstanceOf('App\Http\Requests\ContactRequest', $listPage);

    $listWith = ContactRequest::create('/api/amocrm/contacts', 'GET',
      App::make(AmocrmContactService::class)->list(with: ['leads']));

    $this->assertInstanceOf('App\Http\Requests\ContactRequest', $listWith);
  }
}
