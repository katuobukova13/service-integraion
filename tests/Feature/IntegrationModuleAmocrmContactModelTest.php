<?php

namespace Tests\Feature;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel;
use Illuminate\Foundation\Testing\WithFaker;
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
    };

    $model = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones]);

    $contact = ContactModel::find($model->attributes['id']);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Amocrm\Contact\ContactModel', $contact);
    $this->assertEquals($model->attributes['id'], $contact->attributes['id']);
  }

  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   */
  public function testCreateContact(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;

    for ($i = 0; $i < 3; $i++) {
      $phones[] = $this->faker->phoneNumber;
    };

    $model = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones]);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Amocrm\Contact\ContactModel', $model);
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
    };

    $firstNameUpdate = $this->faker->firstName;
    $phonesUpdate[] = $this->faker->phoneNumber;

    $model = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones]);

    $contact = ContactModel::find($model->attributes['id']);

    $contact->update([
      'first_name' => $firstNameUpdate,
      'cf_phone' => $phonesUpdate
    ]);

    $contact = ContactModel::find($contact->attributes['id']);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Amocrm\Contact\ContactModel', $contact);
    $this->assertEquals($firstNameUpdate, $contact->attributes['first_name']);
    $this->assertEquals($contact->attributes["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($phonesUpdate[0], $contact->attributes["custom_fields_values"][0]['values'][0]);
    $this->assertContains($phones[0], $contact->attributes["custom_fields_values"][0]['values'][1]);
    $this->assertContains($phones[1], $contact->attributes["custom_fields_values"][0]['values'][2]);
  }

  /**
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   */
  public function testListContacts(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;
    $phones1[] = $this->faker->phoneNumber;
    $phones2[] = $this->faker->phoneNumber;
    $phones3[] = $this->faker->phoneNumber;

    $model1 = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones1]);
    $model2 = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones2]);
    $model3 = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "cf_phone" => $phones3]);

    $listFilter = ContactModel::list(filter: ['id' => [$model1->attributes['id'], $model2->attributes['id']]]);

    $this->assertInstanceOf('Illuminate\Support\Collection', $listFilter);
    $this->assertEquals(2, $listFilter->count());
    $this->assertTrue($listFilter->contains('id', $model1->attributes['id']));
    $this->assertTrue($listFilter->contains('id', $model2->attributes['id']));
    $this->assertFalse($listFilter->contains('id', $model3->attributes['id']));

    $listLimit = ContactModel::list(limit: 5);

    $this->assertInstanceOf('Illuminate\Support\Collection', $listLimit);
    $this->assertEquals(5, $listLimit->count());

    $listPage = ContactModel::list(page: 1);
    $this->assertInstanceOf('Illuminate\Support\Collection', $listPage);

    $listWith = ContactModel::list(with: ['leads']);
    $this->assertInstanceOf('Illuminate\Support\Collection', $listWith);
  }
}
