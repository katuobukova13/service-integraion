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
    $payDate = $this->faker->date('d.m.Y');

    $response = $this->post(action(
      [AmocrmOrderController::class, 'store'],
      [
        'first_name' => $contactFirstName,
        'last_name' => $contactLastName,
        'phones' => $contactPhone,
        'emails' => $contactEmail,
        'title' => $title,
        'pay_date' => $payDate,
        'price' => $price,
      ]));

    $this->assertEquals($response['contact']['first_name'], $contactFirstName);
    $this->assertEquals($response['contact']['last_name'], $contactLastName);
    $this->assertEquals($response['contact']['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($response['contact']['emails'][0], $contactEmail[0]);
    $this->assertEquals($response['contact']['phones'][0], $contactPhone[0]);

    $this->assertEquals($response['lead']['name'], $title);
    $this->assertEquals($response['lead']['price'], $price);
    $this->assertEquals($payDate, $response['lead']['pay_date']);

    $this->assertEquals("contacts", $response['link']["to_entity_type"]);
    $this->assertEquals($response['link']["to_entity_id"], $response['contact']['id']);
  }
}
