<?php

namespace Tests\Feature;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class IntegrationModuleAmocrmContactModelTest extends TestCase
{
  use WithFaker;

  /**
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   */
  public function testFindContact(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;

    for ($i = 0; $i < 3; $i++) {
      $phones[] = $this->faker->phoneNumber;
    }

    $model = ContactModel::create([
      "first_name" => $firstName,
      "last_name" => $lastName,
      "cf_phone" => $phones
    ]);

    $contact = ContactModel::find($model->attributes['id']);

    $this->assertInstanceOf(ContactModel::class, $contact);
    $this->assertEquals($model->attributes['id'], $contact->attributes['id']);
  }

  /**
   */
  public function testCreateContact(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;

    for ($i = 0; $i < 3; $i++) {
      $phones[] = $this->faker->phoneNumber;
    }

    $model = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones]);

    $this->assertInstanceOf(ContactModel::class, $model);
    $this->assertEquals($firstName, $model->attributes['first_name']);
    $this->assertEquals($lastName, $model->attributes['last_name']);
    $this->assertEquals($model->attributes["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($phones[0], $model->attributes["custom_fields_values"][0]['values'][0]);
    $this->assertContains($phones[1], $model->attributes["custom_fields_values"][1]['values'][0]);
  }

  /**
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   */
  public function testUpdateContact(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;
    for ($i = 0; $i < 2; $i++) {
      $phones[] = $this->faker->phoneNumber;
    }

    $firstNameUpdate = $this->faker->firstName;
    $phonesUpdate[] = $this->faker->phoneNumber;

    $model = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones]);

    $contact = ContactModel::find($model->attributes['id']);

    $contact->update([
      'first_name' => $firstNameUpdate,
      'cf_phone' => $phonesUpdate
    ]);

    $contact = ContactModel::find($contact->attributes['id']);

    $this->assertInstanceOf(ContactModel::class, $contact);
    $this->assertEquals($firstNameUpdate, $contact->attributes['first_name']);
    $this->assertEquals($contact->attributes["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($phonesUpdate[0], $contact->attributes["custom_fields_values"][0]['values'][0]);
    $this->assertContains($phones[0], $contact->attributes["custom_fields_values"][0]['values'][1]);
    $this->assertContains($phones[1], $contact->attributes["custom_fields_values"][0]['values'][2]);
  }

  /**
   * @throws Exception
   */
  public function testListFilterContacts(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;
    $phones1[] = $this->faker->phoneNumber;
    $phones2[] = $this->faker->phoneNumber;

    $model1 = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones1]);
    $model2 = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones2]);

    $listFilter = ContactModel::list(filter: ['id' => [$model1->attributes['id'], $model2->attributes['id']]]);

    $this->assertInstanceOf(Collection::class, $listFilter);
    $this->assertEquals(2, $listFilter->count());
    $this->assertEquals($listFilter[0]->attributes['id'], $model1->attributes['id']);
    $this->assertEquals($listFilter[1]->attributes['id'], $model2->attributes['id']);
  }

  /**
   * @throws Exception
   */
  public function testListLimitContacts()
  {
    $listLimit = ContactModel::list(limit: 5);

    $this->assertInstanceOf(Collection::class, $listLimit);
    $this->assertEquals(5, $listLimit->count());
  }

  /**
   * @throws Exception
   */
  public function testListPageContacts()
  {
    $listPage = ContactModel::list(page: 1);
    $this->assertInstanceOf(Collection::class, $listPage);
  }

  /**
   * @throws Exception
   */
  public function testListWithLeads()
  {
    $listWith = ContactModel::list(with: ['leads']);
    $this->assertInstanceOf(Collection::class, $listWith);
  }
}
