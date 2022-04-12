<?php

namespace Tests\Feature;

use App\Http\Controllers\AmocrmOrderController;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IntegrationModuleAmocrmOrderControllerStoreTest extends TestCase
{
  use WithFaker;

  public function testStore(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;
    $title = $this->faker->text(15);
    $price = $this->faker->numberBetween(100, 5959);
    $payDate = $this->faker->date();

    $response = $this->post(action(
      [AmocrmOrderController::class, 'store'],
      [
        'first_name' => $contactFirstName,
        'last_name' => $contactLastName,
        'phone' => $contactPhone,
        'email' => $contactEmail,
        'title' => $title,
        'pay_date' => $payDate,
        'price' => $price,
      ]));

    $this->assertEquals($response['contact']['first_name'], $contactFirstName);
    $this->assertEquals($response['contact']['last_name'], $contactLastName);
    $this->assertEquals($response['contact']['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($response['contact']["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.email'));
    $this->assertContains($contactEmail[0], $response['contact']["custom_fields_values"][0]['values'][0]);
    $this->assertEquals($response['contact']["custom_fields_values"][1]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($contactPhone[0], $response['contact']["custom_fields_values"][1]['values'][0]);

    $this->assertEquals($response['lead']['name'], $title);
    $this->assertEquals($response['lead']['price'], $price);
    $this->assertEquals($payDate, substr($response['lead']["custom_fields_values"][0]['values'][0]['value'], 0, 10));

    $this->assertEquals("contacts", $response['link'][0]["to_entity_type"]);
    $this->assertEquals($response['link'][0]["to_entity_id"], $response['contact']['id']);
  }
}
